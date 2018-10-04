<?php

/**
 * Plugin Name: EVE Online Fitting Manager for WordPress
 * Plugin URI: https://github.com/ppfeufer/eve-online-fitting-manager
 * Git URI: https://github.com/ppfeufer/eve-online-fitting-manager
 * Description: A little management tool for your doctrine fittings in your WordPress website. (Best with a theme running with <a href="http://getbootstrap.com/">Bootstrap</a>)
 * Version: 0.13.5-r20180613
 * Author: Rounon Dax
 * Author URI: https://yulaifederation.net
 * Text Domain: eve-online-fitting-manager
 * Domain Path: /l10n
 */

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

namespace WordPress\Plugins\EveOnlineFittingManager;

use \WordPress\Plugins\EveOnlineFittingManager\Libs\GithubUpdater;
use \WordPress\Plugins\EveOnlineFittingManager\Libs\MetaBoxes;
use \WordPress\Plugins\EveOnlineFittingManager\Libs\PluginSettings;
use \WordPress\Plugins\EveOnlineFittingManager\Libs\ResourceLoader\CssLoader;
use \WordPress\Plugins\EveOnlineFittingManager\Libs\ResourceLoader\JavascriptLoader;
use \WordPress\Plugins\EveOnlineFittingManager\Libs\TemplateLoader;
use \WordPress\Plugins\EveOnlineFittingManager\Libs\WpHooks;

const WP_GITHUB_FORCE_UPDATE = true;

\defined('ABSPATH') or die();

// Include the autoloader so we can dynamically include the rest of the classes.
require_once(\trailingslashit(\dirname(__FILE__)) . 'inc/autoloader.php');

class EveOnlineFittingManager {
    private $textDomain = null;
    private $localizationDirectory = null;
    private $pluginDir = null;
    private $pluginUri = null;

    /**
     * Plugin constructor
     *
     * @param boolean $init
     */
    public function __construct() {
        /**
         * Initializing Variables
         */
        $this->textDomain = 'eve-online-fitting-manager';
        $this->pluginDir = \plugin_dir_path(__FILE__);
        $this->pluginUri = \trailingslashit(\plugins_url('/', __FILE__));
        $this->localizationDirectory = \basename(\dirname(__FILE__)) . '/l10n/';

        $this->loadTextDomain();
    }

    /**
     * Initialize the plugin
     */
    public function init() {
        // Firing hooks
        new WpHooks();

        // Loading CSS
        $cssLoader = new CssLoader;
        $cssLoader->init();

        // Loading JavaScript
        $javascriptLoader = new JavascriptLoader;
        $javascriptLoader->init();

        /**
         * start backend only libs
         */
        if(\is_admin()) {
            new PluginSettings;
            new MetaBoxes;
            new TemplateLoader;

            /**
             * Check Github for updates
             */
            $githubConfig = [
                'slug' => \plugin_basename(__FILE__),
                'proper_folder_name' => 'eve-online-fitting-manager',
                'api_url' => 'https://api.github.com/repos/ppfeufer/eve-online-fitting-manager',
                'raw_url' => 'https://raw.github.com/ppfeufer/eve-online-fitting-manager/master',
                'github_url' => 'https://github.com/ppfeufer/eve-online-fitting-manager',
                'zip_url' => 'https://github.com/ppfeufer/eve-online-fitting-manager/archive/master.zip',
                'sslverify' => true,
                'requires' => '4.7',
                'tested' => '4.8',
                'readme' => 'README.md',
                'access_token' => '',
            ];

            new GithubUpdater($githubConfig);
        }
    }

    /**
     * Setting up our text domain for translations
     */
    public function loadTextDomain() {
        if(\function_exists('\load_plugin_textdomain')) {
            \load_plugin_textdomain($this->getTextDomain(), false, $this->getLocalizationDirectory());
        }
    }

    /**
     * Getting the Plugin's Textdomain for translations
     *
     * @return string Plugin Textdomain
     */
    public function getTextDomain() {
        return $this->textDomain;
    }

    /**
     * Getting the Plugin's Localization Directory for translations
     *
     * @return string Plugin Localization Directory
     */
    public function getLocalizationDirectory() {
        return $this->localizationDirectory;
    }
}

/**
 * Start the show ....
 */
function initializePlugin() {
    $fittingManager = new EveOnlineFittingManager;

    /**
     * Initialize the plugin
     *
     * @todo https://premium.wpmudev.org/blog/activate-deactivate-uninstall-hooks/
     */
    $fittingManager->init();
}

// Start the show
initializePlugin();
