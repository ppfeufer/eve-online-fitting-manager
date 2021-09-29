<?php

/*
 * Copyright (C) 2018 ppfeufer
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace WordPress\Plugins\EveOnlineFittingManager\Libs\Helper;

use Exception;
use PclZip;
use WordPress\EsiClient\Swagger;
use WordPress\Plugins\EveOnlineFittingManager\Libs\Singletons\AbstractSingleton;
use wpdb;
use ZipArchive;

defined('ABSPATH') or die();

class UpdateHelper extends AbstractSingleton
{
    /**
     * Database field name for plugin options
     *
     * @var string
     */
    protected string $optionFieldName = 'eve-online-fitting-manager-options';

    /**
     * Database field name for database version
     *
     * @var string
     */
    protected string $pluginDatabaseVersionFieldName = 'eve-online-fitting-manager-database-version';

    /**
     * Database version
     *
     * @var string
     */
    protected $databaseVersion = 20190611;

    /**
     * Database version
     *
     * @var string
     */
    protected $esiClientVersion = 20210929;
    /**
     * hasZipArchive
     *
     * Set true if ZipArchive PHP lib is installed
     *
     * @var bool
     */
    protected bool $hasZipArchive = false;
    /**
     * WordPress Database Instance
     *
     * @var wpdb
     */
    private $wpdb;

    /**
     * Constructor
     *
     * @global wpdb $wpdb
     */
    protected function __construct()
    {
        parent::__construct();

        global $wpdb;

        $this->wpdb = $wpdb;
        $this->hasZipArchive = class_exists('ZipArchive');
    }

    /**
     * Check if the database needs to be updated
     * https://codex.wordpress.org/Creating_Tables_with_Plugins
     */
    public function checkDatabaseForUpdates(): void
    {
        $currentVersion = $this->getCurrentDatabaseVersion();

        if (version_compare($currentVersion, $this->getNewPluginDatabaseVersion()) < 0) {
            $this->updateDatabase();
        }

        /**
         * truncate cache table
         */
        if ($currentVersion < 20190611) {
            $this->truncateCacheTable();
        }

        /**
         * Update database version
         */
        update_option($this->getCurrentDatabaseVersion(), $this->getNewPluginDatabaseVersion());
    }

    /**
     * Getting the current database version
     *
     * @return string
     */
    public function getCurrentDatabaseVersion(): string
    {
        return get_option($this->getDatabaseVersionFieldName());
    }

    /**
     * Returning the database version field name
     *
     * @return string
     */
    public function getDatabaseVersionFieldName(): string
    {
        return $this->pluginDatabaseVersionFieldName;
    }

    /**
     * getNewDatabaseVersion
     *
     * @return int
     */
    public function getNewPluginDatabaseVersion()
    {
        return $this->databaseVersion;
    }

    /**
     * Update the plugin database
     */
    public function updateDatabase(): void
    {
        $this->createEsiCacheTable();
        $this->createMarketDataCacheTable();
    }

    private function createEsiCacheTable(): void
    {
        $charsetCollate = $this->wpdb->get_charset_collate();
        $tableName = $this->wpdb->base_prefix . 'eve_online_esi_cache';

        $sql = "CREATE TABLE $tableName (
            esi_route varchar(255),
            value longtext,
            valid_until varchar(255),
            PRIMARY KEY esi_route (esi_route)
        ) $charsetCollate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta($sql);
    }

    private function createMarketDataCacheTable(): void
    {
        $charsetCollate = $this->wpdb->get_charset_collate();
        $tableName = $this->wpdb->base_prefix . 'eve_online_market_data_cache';

        $sql = "CREATE TABLE $tableName (
            cache_key varchar(255),
            value longtext,
            valid_until varchar(255),
            PRIMARY KEY cache_key (cache_key)
        ) $charsetCollate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta($sql);
    }

    private function truncateCacheTable(): void
    {
        $tableName = $this->wpdb->base_prefix . 'eve_online_esi_cache';

        $sql = "TRUNCATE $tableName;";
        $this->wpdb->query($sql);
    }

    public function updatePluginOptions(): void
    {
        $defaultSettings = PluginHelper::getInstance()->getPluginDefaultSettings();
        $pluginSettings = PluginHelper::getInstance()->getPluginSettings(false);

        if (is_array($pluginSettings)) {
            $newOptions = array_merge($defaultSettings, $pluginSettings);
        } else {
            $newOptions = $defaultSettings;
        }

        // Update the options
        update_option($this->getOptionFieldName(), $newOptions);

        // Update the DB Version
        update_option($this->getDatabaseVersionFieldName(), $this->getNewPluginDatabaseVersion());
    }

    public function getOptionFieldName(): string
    {
        return $this->optionFieldName;
    }

    /**
     * Check if the ESI clients needs to be updated
     * @throws Exception
     */
    public function checkEsiClientForUpdates(): void
    {
        $esiClientCurrentVersion = null;

        /**
         * Check for current ESI client version
         */
        if (class_exists('\WordPress\EsiClient\Swagger')) {
            $esiClient = new Swagger;

            if (method_exists($esiClient, 'getEsiClientVersion')) {
                $esiClientCurrentVersion = $esiClient->getEsiClientVersion();
            }
        }

        // backwards compatibility with older ESI clients
        if (is_null($esiClientCurrentVersion) && file_exists(WP_CONTENT_DIR . '/EsiClient/client_version')) {
            $esiClientCurrentVersion = trim(file_get_contents(WP_CONTENT_DIR . '/EsiClient/client_version'));
        }

        if (version_compare($esiClientCurrentVersion, $this->getNewEsiClientVersion()) < 0) {
            $this->updateEsiClient($this->getNewEsiClientVersion());
        }
    }

    /**
     * getNewEsiClientVersion
     *
     * @return int
     */
    public function getNewEsiClientVersion()
    {
        return $this->esiClientVersion;
    }

    /**
     * Updateing ESI client if needed
     *
     * @throws Exception
     */
    private function updateEsiClient(string $version = null): void
    {
        $remoteZipFile = 'https://github.com/ppfeufer/wp-esi-client/archive/master.zip';
        $dirInZipFile = '/wp-esi-client-master';

        if (!is_null($version)) {
            $remoteZipFile = 'https://github.com/ppfeufer/wp-esi-client/archive/v' . $version . '.zip';
            $dirInZipFile = '/wp-esi-client-' . $version;
        }

        $esiClientZipFile = WP_CONTENT_DIR . '/uploads/EsiClient.zip';

        wp_remote_get($remoteZipFile, [
            'timeout' => 300,
            'stream' => true,
            'filename' => $esiClientZipFile
        ]);

        if (is_dir(WP_CONTENT_DIR . '/EsiClient/')) {
            $this->rrmdir(WP_CONTENT_DIR . '/EsiClient/');
        }

        // extract using ZipArchive
        if ($this->hasZipArchive === true) {
            $zip = new ZipArchive;
            if (!$zip->open($esiClientZipFile)) {
                throw new Exception('PHP-ZIP: Unable to open the Esi Client zip file');
            }

            if (!$zip->extractTo(WP_CONTENT_DIR)) {
                throw new Exception('PHP-ZIP: Unable to extract Esi Client zip file');
            }

            $zip->close();
        }

        // extract using PclZip
        if ($this->hasZipArchive === false) {
            require_once(ABSPATH . 'wp-admin/includes/class-pclzip.php');

            $zip = new PclZip($esiClientZipFile);

            if (!$zip->extract(PCLZIP_OPT_PATH, WP_CONTENT_DIR)) {
                throw new Exception('PHP-ZIP: Unable to extract Esi Client zip file');
            }
        }

        rename(WP_CONTENT_DIR . $dirInZipFile, WP_CONTENT_DIR . '/EsiClient/');

        unlink($esiClientZipFile);
    }

    /**
     * Recursively remove directory
     *
     * @param string $dir
     */
    private function rrmdir(string $dir): void
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);

            foreach ($objects as $object) {
                if ($object !== "." && $object !== "..") {
                    if (is_dir($dir . "/" . $object)) {
                        $this->rrmdir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }

            rmdir($dir);
        }
    }
}
