<?php

/*
 * Copyright (C) 2017 ppfeufer
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

namespace WordPress\Plugins\EveOnlineFittingManager\Libs;

use stdClass;
use WP_Error;
use const WordPress\Plugins\EveOnlineFittingManager\WP_GITHUB_FORCE_UPDATE;

defined('ABSPATH') or die();

/**
 * @version 1.6
 * @author Joachim Kudish <info@jkudish.com>
 * @link http://jkudish.com
 * @package WP_GitHub_Updater
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @copyright Copyright (c) 2011-2013, Joachim Kudish
 *
 * GNU General Public License, Free Software Foundation
 * <http://creativecommons.org/licenses/GPL/2.0/>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */
class GithubUpdater
{
    /**
     * GitHub Updater version
     */
    protected const VERSION = 1.6;

    /**
     * the config for the updater
     *
     * @var $config
     * @access public
     */
    public $config;

    /**
     * any config that is missing from the initialization of this instance
     *
     * @var array $missingConfig
     * @access public
     */
    public array $missingConfig;

    /**
     * temporiraly store the data fetched from GitHub, allows us to only load the data once per class instance
     *
     * @var $githubData
     * @access private
     */
    private $githubData;

    /**
     * Class Constructor
     *
     * @param array $config the configuration required for the updater to work
     * @return void
     * @see has_minimum_config()
     * @since 1.0
     */
    public function __construct(array $config = [])
    {
        $defaults = [
            'slug' => plugin_basename(__FILE__),
            'proper_folder_name' => dirname(plugin_basename(__FILE__)),
            'sslverify' => true,
            'access_token' => '',
        ];

        $this->config = wp_parse_args($config, $defaults);

        // if the minimum config isn't set, issue a warning and bail
        if (!$this->hasMinimumConfig()) {
            $message = __('The GitHub Updater was initialized without the minimum required configuration, please check the config in your plugin. The following params are missing: ', 'eve-online-fitting-manager');
            $message .= implode(',', $this->missingConfig);

            _doing_it_wrong(__CLASS__, $message, self::VERSION);

            return;
        }

        $this->setDefaults();
        $this->init();
    }

    /**
     * Check if the minimum configuration is given
     *
     * @return boolean
     */
    public function hasMinimumConfig(): bool
    {
        $this->missingConfig = [];

        $requiredConfigParams = [
            'api_url',
            'raw_url',
            'github_url',
            'zip_url',
            'requires',
            'tested',
            'readme',
        ];

        foreach ($requiredConfigParams as $required_param) {
            if (empty($this->config[$required_param])) {
                $this->missingConfig[] = $required_param;
            }
        }

        return (empty($this->missingConfig));
    }

    /**
     * Set defaults
     *
     * @return void
     * @since 1.2
     */
    public function setDefaults(): void
    {
        if (!empty($this->config['access_token'])) {
            // See Downloading a zipball (private repo) https://help.github.com/articles/downloading-files-from-the-command-line
            $parsedUrl = parse_url($this->config['zip_url']); // $scheme, $host, $path

            $zipUrl = $parsedUrl['scheme'] . '://api.github.com/repos' . $parsedUrl['path'];
            $zipUrl = add_query_arg(['access_token' => $this->config['access_token']], $zipUrl);

            $this->config['zip_url'] = $zipUrl;
        }

        if (!isset($this->config['new_version'])) {
            $this->config['new_version'] = $this->getNewVersion();
        }

        if (!isset($this->config['last_updated'])) {
            $this->config['last_updated'] = $this->getDate();
        }

        if (!isset($this->config['description'])) {
            $this->config['description'] = $this->getDescription();
        }

        $plugin_data = $this->getPluginData();
        if (!isset($this->config['plugin_name'])) {
            $this->config['plugin_name'] = $plugin_data['Name'];
        }

        if (!isset($this->config['version'])) {
            $this->config['version'] = $plugin_data['Version'];
        }

        if (!isset($this->config['author'])) {
            $this->config['author'] = $plugin_data['Author'];
        }

        if (!isset($this->config['homepage'])) {
            $this->config['homepage'] = $plugin_data['PluginURI'];
        }

        if (!isset($this->config['readme'])) {
            $this->config['readme'] = 'README.md';
        }
    }

    /**
     * Get New Version from GitHub
     *
     * @return int $version the version number
     * @since 1.0
     */
    public function getNewVersion()
    {
        $version = get_site_transient(md5($this->config['slug']) . '_new_version');

        if ((!isset($version) || !$version || $version === '') || $this->overruleTransients()) {
            $version = false;
            $rawResponse = $this->remoteGet(trailingslashit($this->config['raw_url']) . basename($this->config['slug']));

            if (is_array($rawResponse) && !empty($rawResponse['body'])) {
                preg_match('/.*Version\:\s*(.*)$/mi', $rawResponse['body'], $matches);
            }

            if (!empty($matches[1])) {
                $version = $matches[1];
            }

            // back compat for older readme version handling
            // only done when there is no version found in file name
            if ($version === false) {
                $rawResponse = $this->remoteGet(trailingslashit($this->config['raw_url']) . $this->config['readme']);

                if (is_wp_error($rawResponse)) {
                    return $version;
                }

                preg_match('#^\s*`*~Current Version\:\s*([^~]*)~#im', $rawResponse['body'], $__version);

                if (isset($__version[1])) {
                    $version_readme = $__version[1];

                    if (version_compare($version, $version_readme) === -1) {
                        $version = $version_readme;
                    }
                }
            }

            // refresh every 6 hours
            if ($version !== false) {
                set_site_transient(md5($this->config['slug']) . '_new_version', $version, 60 * 60 * 6);
            }
        }

        return $version;
    }

    /**
     * Check wether or not the transients need to be overruled and API needs to be called for every single page load
     *
     * @return bool overrule or not
     */
    public function overruleTransients(): bool
    {
        return (defined('\WordPress\Plugins\EveOnlineFittingManager\WP_GITHUB_FORCE_UPDATE') && WP_GITHUB_FORCE_UPDATE);
    }

    /**
     * Interact with GitHub
     *
     * @param string $query
     *
     * @return array|WP_Error
     * @since 1.6
     */
    public function remoteGet(string $query)
    {
        // set timeout
        add_filter('http_request_timeout', [$this, 'httpRequestTimeout']);

        // set sslverify for zip download
        add_filter('http_request_args', [$this, 'httpRequestSslVerify'], 10, 2);

        if (!empty($this->config['access_token'])) {
            $query = add_query_arg(['access_token' => $this->config['access_token']], $query);
        }

        $rawResponse = wp_remote_get($query, [
            'sslverify' => $this->config['sslverify']
        ]);

        // Remove filters so we don't globally impact http requets.
        remove_filter('http_request_timeout', [$this, 'http_request_timeout']);
        remove_filter('http_request_args', [$this, 'http_request_sslverify']);

        return $rawResponse;
    }

    /**
     * Get update date
     *
     * @return string $date the date
     * @since 1.0
     */
    public function getDate()
    {
        $githubData = $this->getGithubData();

        return (!empty($githubData->updated_at)) ? date('Y-m-d', strtotime($githubData->updated_at)) : false;
    }

    /**
     * Get GitHub Data from the specified repository
     *
     * @return array|bool $github_data the data
     * @since 1.0
     */
    public function getGithubData()
    {
        $githubData = null;

        if (isset($this->githubData) && !empty($this->githubData)) {
            $githubData = $this->githubData;
        }

        if ($githubData === null) {
            $githubData = get_site_transient(md5($this->config['slug']) . '_github_data');

            if ((!isset($githubData) || !$githubData || $githubData === '') || $this->overruleTransients()) {
                $githubRemoteData = $this->remoteGet($this->config['api_url']);

                if (is_wp_error($githubRemoteData)) {
                    return false;
                }

                $githubData = json_decode($githubRemoteData['body']);

                // refresh every 6 hours
                set_site_transient(md5($this->config['slug']) . '_github_data', $githubData, 60 * 60 * 6);
            }

            // Store the data in this class instance for future calls
            $this->githubData = $githubData;
        }

        return $githubData;
    }

    /**
     * Get plugin description
     *
     * @return string $description the description
     * @since 1.0
     */
    public function getDescription()
    {
        $githubData = $this->getGithubData();

        return (!empty($githubData->description)) ? $githubData->description : false;
    }

    /**
     * Get Plugin data
     *
     * @return array $data the data
     * @since 1.0
     */
    public function getPluginData(): array
    {
        include_once(ABSPATH . '/wp-admin/includes/plugin.php');

        return get_plugin_data(WP_PLUGIN_DIR . '/' . $this->config['slug']);
    }

    /**
     * Fire all WordPress actions
     */
    public function init(): void
    {
        add_filter('pre_set_site_transient_update_plugins', [$this, 'apiCheck']);

        // Hook into the plugin details screen
        add_filter('plugins_api', [$this, 'getPluginInfo'], 10, 3);
        add_filter('upgrader_post_install', [$this, 'upgraderPostInstall'], 10, 3);
    }

    /**
     * Callback fn for the http_request_timeout filter
     *
     * @return int timeout value
     * @since 1.0
     */
    public function httpRequestTimeout(): int
    {
        return 2;
    }

    /**
     * Callback fn for the http_request_args filter
     *
     * @param array $args
     * @param string $url
     *
     * @return array
     */
    public function httpRequestSslVerify(array $args, string $url): array
    {
        if ($this->config['zip_url'] === $url) {
            $args['sslverify'] = $this->config['sslverify'];
        }

        return $args;
    }

    /**
     * Hook into the plugin update check and connect to GitHub
     *
     * @param object $transient the plugin data transient
     * @return object $transient updated plugin data transient
     * @since 1.0
     */
    public function apiCheck(object $transient): object
    {
        // Check if the transient contains the 'checked' information
        // If not, just return its value without hacking it
        if (empty($transient->checked)) {
            return $transient;
        }

        // check the version and decide if it's new
        $update = version_compare($this->config['new_version'], $this->config['version']);

        if ($update === 1) {
            $response = new stdClass;
            $response->new_version = $this->config['new_version'];
            $response->slug = $this->config['proper_folder_name'];
            $response->url = add_query_arg(['access_token' => $this->config['access_token']], $this->config['github_url']);
            $response->package = $this->config['zip_url'];

            $transient->response[$this->config['slug']] = $response;
        }

        return $transient;
    }

    /**
     * Get Plugin info
     *
     * @param bool $false always false
     * @param string $action the API function being performed
     * @param object $response
     * @return object|bool
     * @since 1.0
     */
    public function getPluginInfo(bool $false, string $action, object $response)
    {
        // Check if this call API is for the right plugin
        if (!isset($response->slug) || $response->slug !== $this->config['slug']) {
            return false;
        }

        $response->slug = $this->config['slug'];
        $response->plugin_name = $this->config['plugin_name'];
        $response->version = $this->config['new_version'];
        $response->author = $this->config['author'];
        $response->homepage = $this->config['homepage'];
        $response->requires = $this->config['requires'];
        $response->tested = $this->config['tested'];
        $response->downloaded = 0;
        $response->last_updated = $this->config['last_updated'];
        $response->sections = ['description' => $this->config['description']];
        $response->download_link = $this->config['zip_url'];

        return $response;
    }

    /**
     * Upgrader/Updater
     * Move & activate the plugin, echo the update message
     *
     * @param bool $true always true
     * @param mixed $hook_extra not used
     * @param array $result the result of the move
     * @return array $result the result of the move
     * @since 1.0
     */
    public function upgraderPostInstall(bool $true, $hook_extra, array $result): array
    {
        global $wp_filesystem;

        // Move & Activate
        $proper_destination = WP_PLUGIN_DIR . '/' . $this->config['proper_folder_name'];
        $wp_filesystem->move($result['destination'], $proper_destination);
        $result['destination'] = $proper_destination;
        $activate = activate_plugin(WP_PLUGIN_DIR . '/' . $this->config['slug']);

        // Output the update message
        $fail = __('The plugin has been updated, but could not be reactivated. Please reactivate it manually.', 'eve-online-fitting-manager');
        $success = __('Plugin reactivated successfully.', 'eve-online-fitting-manager');

        echo is_wp_error($activate) ? $fail : $success;

        return $result;
    }
}
