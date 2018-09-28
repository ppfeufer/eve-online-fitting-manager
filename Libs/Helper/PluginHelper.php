<?php

/**
 * Copyright (C) 2017 Rounon Dax
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace WordPress\Plugin\EveOnlineFittingManager\Libs\Helper;

\defined('ABSPATH') or die();

class PluginHelper {
    public static $optionName = 'eve-online-fitting-manager-options';
    public static $dbVersionFieldName = 'eve-online-fitting-manager-database-version';
    public static $databaseVersion = '20180329';

    /**
     * Getting the Plugin Path
     *
     * @param string $file
     * @return string
     */
    public static function getPluginPath($file = '') {
        return \WP_PLUGIN_DIR . '/eve-online-fitting-manager/' . $file;
    }

    /**
     * Getting the Plugin URI
     *
     * @param string $file
     * @return string
     */
    public static function getPluginUri($file = '') {
        return \WP_PLUGIN_URL . '/eve-online-fitting-manager/' . $file;
    }

    /**
     * Getting thew options field name
     *
     * @return string
     */
    public static function getOptionFieldName() {
        return self::$optionName;
    }

    /**
     * Getting the Database Version field name
     *
     * @return string
     */
    public static function getDatabaseVersionFieldName() {
        return self::$dbVersionFieldName;
    }

    /**
     * Getting the Database Version from plugin
     *
     * @return string
     */
    public static function getNewPluginDatabaseVersion() {
        return self::$databaseVersion;
    }

    /**
     * Returning the default settings for this plugin
     *
     * @return array
     */
    public static function getPluginDefaultSettings() {
        $defaultSettings = [
            'edk-killboard-host' => '',
            'edk-killboard-user' => '',
            'edk-killboard-name' => '',
            'edk-killboard-password' => '',
            'market-data-api' => 'eve-marketer',
            'template-image-settings' => [
                'show-ship-images-in-loop' => '',
                'show-doctrine-images-in-loop' => '',
                'use-image-cache' => ''
            ],
            'template-detail-parts-settings' => [
                'show-visual-fitting' => 'yes',
                'show-osmium-link' => 'yes',
                'show-ship-description' => 'yes',
                'show-copy-eft' => 'yes',
                'show-copy-permalink' => 'yes',
                'show-market-data' => 'yes',
                'show-doctrines' => 'yes',
            ]
        ];

        return $defaultSettings;
    }

    /**
     * Getting the Database Version from options
     *
     * @return string
     */
    public static function getPluginDatabaseVersion() {
        return \get_option(self::getDatabaseVersionFieldName());
    }

    /**
     * Update the plugin default settings if needed
     */
    public static function updateDatabase() {
        $defaultSettings = self::getPluginDefaultSettings();
        $pluginSettings = self::getPluginSettings(false);

        if(\is_array($pluginSettings)) {
            $newOptions = \array_merge($defaultSettings, $pluginSettings);
        } else {
            $newOptions = $defaultSettings;
        }

        // Update the options
        \update_option(self::getOptionFieldName(), $newOptions);

        // Update the DB Version
        \update_option(self::getDatabaseVersionFieldName(), self::getNewPluginDatabaseVersion());
    }

    /**
     * Getting the Plugin's settings
     *
     * @param boolean $merged Merge with default settings (true/false)
     * @return array
     */
    public static function getPluginSettings($merged = true) {
        if($merged === true) {
            $pluginSettings = \get_option(self::getOptionFieldName(), self::getPluginDefaultSettings());
        } else {
            $pluginSettings = \get_option(self::getOptionFieldName());
        }

        return $pluginSettings;
    }

    /**
     * checking for other plugins we might be able to use
     *
     * @param type $plugin
     * @return boolean
     */
    public static function checkPluginDependencies($plugin) {
        $returnValue = false;

        if(\class_exists($plugin)) {
            $returnValue = true;
        }

        return $returnValue;
    }

    /**
     * Alias for is_active_sidebar()
     *
     * @param string $sidebarPosition
     * @return boolean
     * @uses is_active_sidebar() Whether a sidebar is in use.
     */
    public static function hasSidebar($sidebarPosition) {
        return \is_active_sidebar($sidebarPosition);
    }

    public static function getMainContentColClasses($echo = false) {
        $contentColClass = 'col-lg-12 col-md-12 col-sm-12 col-12';

        if(self::hasSidebar('sidebar-fitting-manager')) {
            $contentColClass = 'col-lg-9 col-md-9 col-sm-9 col-9';
        } else {
            $contentColClass = 'col-lg-12 col-md-12 col-sm-12 col-12';
        }

        if($echo === true) {
            echo $contentColClass;
        } else {
            return $contentColClass;
        }
    }

    public static function getLoopContentClasses($echo = false) {
        $contentColClass = 'col-lg-3 col-md-4 col-sm-6 col-xs-12';

        if(self::hasSidebar('sidebar-fitting-manager')) {
            $contentColClass = 'col-lg-4 col-md-6 col-sm-12 col-xs-12';
        } else {
            $contentColClass = 'col-lg-3 col-md-4 col-sm-6 col-xs-12';
        }

        if($echo === true) {
            echo $contentColClass;
        } else {
            return $contentColClass;
        }
    }
}
