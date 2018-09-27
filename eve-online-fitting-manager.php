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

namespace WordPress\Plugin\EveOnlineFittingManager;

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
        new Libs\WpHooks();

        // Loading CSS
        $cssLoader = new Libs\ResourceLoader\CssLoader;
        $cssLoader->init();

        // Loading JavaScript
        $javascriptLoader = new Libs\ResourceLoader\JavascriptLoader;
        $javascriptLoader->init();

        new Libs\PostType;
        new Libs\Shortcodes;
        new Libs\MarketData;

        $widgets = new Libs\Widgets;
        $widgets->init();

        /**
         * start backend only libs
         */
        if(\is_admin()) {
            new Libs\PluginSettings;
            new Libs\MetaBoxes;
            new Libs\TemplateLoader;

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

            new Libs\GithubUpdater($githubConfig);
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
