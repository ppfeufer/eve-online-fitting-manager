<?php
/**
 * Plugin Name: EVE Online Fitting Manager for WordPress
 * Plugin URI: https://github.com/ppfeufer/eve-online-fitting-manager
 * Git URI: https://github.com/ppfeufer/eve-online-fitting-manager
 * Description: A little management tool for your doctrine fittings for your WordPress website. (Best with a theme running with <a href="http://getbootstrap.com/">Bootstrap</a>)
 * Version: 1.0
 * Author: Rounon Dax
 * Author URI: http://yulaifederation.net
 * Text Domain: eve-online-fitting-manager
 * Domain Path: /l10n
 */

namespace WordPress\Plugin\EveOnlineFittingManager;

class EveOnlineFittingManager {
	private $textDomain = null;
	private $localizationDirectory = null;
	private $pluginDir = null;
	private $pluginUri = null;
	private $optionName = null;
	private $dbVersionFieldName = null;
	private $databaseVersion = null;

	public function __construct($init = false) {
		/**
		 * Initializing Variables
		 */
		$this->textDomain = 'eve-online-fitting-manager';
		$this->localizationDirectory = $this->getPluginDir() . '/l10n/';
		$this->pluginDir =  \plugin_dir_path(__FILE__);
		$this->pluginUri = \trailingslashit(\plugins_url('/', __FILE__));
		$this->optionName = 'eve-online-fitting-manager-options';
		$this->dbVersionFieldName = 'eve-online-fitting-manager-database-version';
		$this->databaseVersion = '20160906';

		$this->loadTextDomain();

		/**
		 * Initialize the plugin
		 */
		if($init === true) {
			$this->init();
		} // END if($init === true)
	} // END public function __construct()

	public function init() {
		$this->loadLibs();
		$this->checkDatabaseUpdate();

		new Libs\PostType;

		/**
		 * start backend libs
		 */
		if(\is_admin()) {
//			new Libs\Backend\GithubPluginUpdater(__FILE__, 'ppfeufer', 'eve-online-fitting-manager');
			new Libs\PluginSettings;
		} // END if(\is_admin())
	} // END public function init()

	private function checkDatabaseUpdate() {
		$currentPluginDatabaseVersion = $this->getCurrentPluginDatabaseVersion();
		$pluginDatabaseVersion = $this->getPluginDatabaseVersion();

		if($pluginDatabaseVersion !== $currentPluginDatabaseVersion) {
			$this->updateDatabase();
		} // END if($pluginDatabaseVersion !== $currentPluginDatabaseVersion)
	} // END private function checkDatabaseUpdate()

	private function updateDatabase() {
		$defaultSettings = $this->getDefaultSettings();
		$pluginSettings = $this->getPluginSettings(false);

		if(\is_array($pluginSettings)) {
			$newOptions = \array_merge($defaultSettings, $pluginSettings);
		} else {
			$newOptions = $defaultSettings;
		} // END if(\is_array($pluginSettings))

		// Update the options
		\update_option($this->getOptionFieldName(), $newOptions);

		// Update the DB Version
		\update_option($this->getDatabaseVersionFieldName(), $this->getCurrentPluginDatabaseVersion());
	} // END private function updateDatabase()

	/**
	 * Getting the Plugin's settings
	 *
	 * @param boolean $merged Merge with default settings (true/false)
	 * @return array
	 */
	public function getPluginSettings($merged = true) {
		if($merged === true) {
			$pluginSettings = \get_option($this->getOptionFieldName(), $this->getDefaultSettings());
		} else {
			$pluginSettings = \get_option($this->getOptionFieldName());
		} // END if($merged === true)

		return $pluginSettings;
	} // END public function getPluginSettings($merged = true)

	/**
	 * Returning the deualt settings for this plugin
	 *
	 * @return array
	 */
	public function getDefaultSettings() {
		$defaultSettings = array(
			'edk-killboard-host' => '',
			'edk-killboard-user' => '',
			'edk-killboard-name' => '',
			'edk-killboard-password' => ''
		);

		return $defaultSettings;
	} // END public function getDefaultSettings()

	/**
	 * Getting thew options field name
	 *
	 * @return string
	 */
	public function getOptionFieldName() {
		return $this->optionName;
	} // END public function getOptionFieldName()

	/**
	 * Getting the Database Version field name
	 *
	 * @return string
	 */
	public function getDatabaseVersionFieldName() {
		return $this->dbVersionFieldName;
	} // END public function getDatabaseVersionFieldName()

	/**
	 * Getting the Database Version from plugin
	 *
	 * @return string
	 */
	private function getCurrentPluginDatabaseVersion() {
		return $this->databaseVersion;
	} // END private function getCurrentPluginDatabaseVersion()

	/**
	 * Getting the Database Version from options
	 *
	 * @return string
	 */
	private function getPluginDatabaseVersion() {
		return \get_option($this->getDatabaseVersionFieldName());
	} // END private function getDatabaseVersion()

	/**
	 * Setting up our text domain for translations
	 */
	public function loadTextDomain() {
		if(\function_exists('\load_plugin_textdomain')) {
			\load_plugin_textdomain($this->getTextDomain(), false, $this->getLocalizationDirectory());
		} // END if(function_exists('\load_plugin_textdomain'))
	} // END public function addTextDomain()

	/**
	 * Loading all libs
	 */
	public function loadLibs() {
		/**
		 * Load Backend Libs
		 */
//		if(\is_admin()) {
//			foreach(\glob($this->getPluginDir() . 'libs/backend/*.php') as $backendLib) {
//				include_once($backendLib);
//			} // END foreach(\glob($this->getPluginDir() . 'libs/backend/*.php') as $lib)
//		} // END if(\is_admin())

		/**
		 * Load Frontend Libs
		 */
//		if(!\is_admin()) {
//			foreach(\glob($this->getPluginDir() . 'libs/frontend/*.php') as $frontendLib) {
//				include_once($frontendLib);
//			} // END foreach(\glob($this->getPluginDir() . 'libs/frontend/*.php') as $lib)
//		} // END if(!\is_admin())
		foreach(\glob($this->getPluginDir() . 'libs/*.php') as $lib) {
			include_once($lib);
		} // END foreach(\glob($this->getPluginDir() . 'libs/*.php') as $lib)
	} // END public function loadLibs()

	/**
	 * Returning the Plugins relative directory with trailing slash
	 *
	 * @return string Plugin Directory
	 */
	public function getPluginDir() {
		return $this->pluginDir;
	} // END public function getPluginDir()

	/**
	 * Returning the Plugins URL with trailing slash
	 *
	 * @return string Plugin URL
	 */
	public function getPluginUri() {
		return $this->pluginUri;
	} // END public function getPluginUri()

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
$eveOnlineFittingManager = new EveOnlineFittingManager(true);
