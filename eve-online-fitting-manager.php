<?php
/**
 * Plugin Name: EVE Online Fitting Manager for WordPress
 * Plugin URI: https://github.com/ppfeufer/eve-online-fitting-manager
 * Git URI: https://github.com/ppfeufer/eve-online-fitting-manager
 * Description: A little management tool for your doctrine fittings in your WordPress website. (Best with a theme running with <a href="http://getbootstrap.com/">Bootstrap</a>)
 * Version: 0.6-dev
 * Author: Rounon Dax
 * Author URI: http://yulaifederation.net
 * Text Domain: eve-online-fitting-manager
 * Domain Path: /l10n
 */

namespace WordPress\Plugin\EveOnlineFittingManager;
const WP_GITHUB_FORCE_UPDATE = true;

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
		$this->pluginDir =  \plugin_dir_path(__FILE__);
		$this->pluginUri = \trailingslashit(\plugins_url('/', __FILE__));
		$this->localizationDirectory = $this->pluginDir . '/l10n/';

		$this->loadTextDomain();
	} // END public function __construct()

	/**
	 * Initialize the plugin
	 */
	public function init() {
		$this->checkDatabaseUpdate();

		// Loading CSS
		$cssLoader = new ResourceLoader\CssLoader;
		$cssLoader->init();

		// Loading JavaScript
		$javascriptLoader = new ResourceLoader\JavascriptLoader;
		$javascriptLoader->init();

		\add_action('init', [$this, 'setThumbnailsSizes']);
		\add_action('pre_get_posts', [$this, 'customPageQueryVars']);

		\add_filter('query_vars', [$this, 'addQueryVarsFilter']);

		new Libs\PostType;
		new Libs\Shortcodes;
		new Libs\MarketData;

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
		} // END if(\is_admin())
	} // END public function init()

	/**
	 * Set thumbnail sizes
	 */
	public function setThumbnailsSizes() {
		/**
		 * Thumbnails used for the plugin
		 * Compatibilty with Fly Dynamic Image Resizer plugin
		 */
		if(\function_exists('\fly_add_image_size')) {
			\fly_add_image_size('header-image', 1680, 500, true);
			\fly_add_image_size('fitting-helper-post-loop-thumbnail', 768, 432, true);
		} else {
			\add_image_size('header-image', 1680, 500, true);
			\add_image_size('fitting-helper-post-loop-thumbnail', 768, 432, true);
		} // END if(\function_exists('\fly_add_image_size'))
	} // END public function setThumbnailsSizes()

	/**
	 * Check if the plugin settings have to be updated
	 */
	private function checkDatabaseUpdate() {
		$currentPluginDatabaseVersion = Helper\PluginHelper::getCurrentPluginDatabaseVersion();
		$pluginDatabaseVersion = Helper\PluginHelper::getPluginDatabaseVersion();

		if($pluginDatabaseVersion !== $currentPluginDatabaseVersion) {
			Helper\PluginHelper::updateDatabase();
		} // END if($pluginDatabaseVersion !== $currentPluginDatabaseVersion)
	} // END private function checkDatabaseUpdate()

	/**
	 * Customized query vars for our search function
	 *
	 * @param \WP_Query $query
	 * @return \WP_Query
	 */
	public function customPageQueryVars(\WP_Query $query) {
		if(!\is_admin() && $query->is_main_query()) {
			if(isset($query->tax_query->queries['0']['taxonomy']) && $query->tax_query->queries['0']['taxonomy'] === 'fitting-doctrines') {
				$query->set('posts_per_page', 9999);
				$query->set('orderby', 'title');
				$query->set('order', 'ASC');
			} // END if($query->tax_query->queries['0']['taxonomy'] === 'fitting-doctrines')

			if(isset($query->tax_query->queries['0']['taxonomy']) && $query->tax_query->queries['0']['taxonomy'] === 'fitting-ships') {
				$query->set('posts_per_page', 9999);
				$query->set('orderby', 'title');
				$query->set('order', 'ASC');
			} // END if($query->tax_query->queries['0']['taxonomy'] === 'fitting-ships')
		} // END if(!\is_admin() && $query->is_main_query())

		return $query;
	} // END public function customPageQueryVars($query)

	/**
	 * Add our own seach key to the search query vars
	 *
	 * @param array $queryVars
	 * @return array
	 */
	public function addQueryVarsFilter($queryVars) {
		$queryVars[] = 'fitting_search';

		return $queryVars;
	} // END public function addQueryVarsFilter($queryVars)

	/**
	 * Setting up our text domain for translations
	 */
	public function loadTextDomain() {
		if(\function_exists('\load_plugin_textdomain')) {
			\load_plugin_textdomain($this->getTextDomain(), false, $this->getLocalizationDirectory());
		} // END if(function_exists('\load_plugin_textdomain'))
	} // END public function addTextDomain()

	/**
	 * Getting the Plugin's Textdomain for translations
	 *
	 * @return string Plugin Textdomain
	 */
	public function getTextDomain() {
		return $this->textDomain;
	} // END public function getTextDomain()

	/**
	 * Getting the Plugin's Localization Directory for translations
	 *
	 * @return string Plugin Localization Directory
	 */
	public function getLocalizationDirectory() {
		return $this->localizationDirectory;
	} // END public function getLocalizationDirectory()
} // END class EveOnlineFittingManager

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
} // END function initializePlugin()

// Start the show
\add_action('plugins_loaded', 'WordPress\Plugin\EveOnlineFittingManager\initializePlugin');
