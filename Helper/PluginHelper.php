<?php

namespace WordPress\Plugin\EveOnlineFittingManager\Helper;

use WordPress\Plugin\EveOnlineFittingManager;

\defined('ABSPATH') or die();

class PluginHelper {
	public static $optionName = 'eve-online-fitting-manager-options';
	public static $dbVersionFieldName = 'eve-online-fitting-manager-database-version';
	public static $databaseVersion = '20170804';

	/**
	 * Getting the Plugin Path
	 *
	 * @param string $file
	 * @return string
	 */
	public static function getPluginPath($file = '') {
		return \trailingslashit(\plugin_dir_path(dirname(__FILE__))) . $file;
	}

	/**
	 * Getting the Plugin URI
	 *
	 * @param string $file
	 * @return string
	 */
	public static function getPluginUri($file = '') {
		return \plugins_url($file, dirname(__FILE__));
	} // END public function getThemeCacheUri()

	/**
	 * Getting thew options field name
	 *
	 * @return string
	 */
	public static function getOptionFieldName() {
		return self::$optionName;
	} // END public function getOptionFieldName()

	/**
	 * Getting the Database Version field name
	 *
	 * @return string
	 */
	public static function getDatabaseVersionFieldName() {
		return self::$dbVersionFieldName;
	} // END public function getDatabaseVersionFieldName()

	/**
	 * Getting the Database Version from plugin
	 *
	 * @return string
	 */
	public static function getCurrentPluginDatabaseVersion() {
		return self::$databaseVersion;
	} // END private function getCurrentPluginDatabaseVersion()

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
			'template-image-settings' => [
				'show-ship-images-in-loop' => '',
				'show-doctrine-images-in-loop' => ''
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
	} // END public static function getPluginDefaultSettings()

	/**
	 * Getting the Database Version from options
	 *
	 * @return string
	 */
	public static function getPluginDatabaseVersion() {
		return \get_option(self::getDatabaseVersionFieldName());
	} // END private function getDatabaseVersion()

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
		} // END if(\is_array($pluginSettings))

		// Update the options
		\update_option(self::getOptionFieldName(), $newOptions);

		// Update the DB Version
		\update_option(self::getDatabaseVersionFieldName(), self::getCurrentPluginDatabaseVersion());
	} // END private function updateDatabase()

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
		} // END if($merged === true)

		return $pluginSettings;
	} // END public function getPluginSettings($merged = true)

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
		} // END if(\class_exists('EVEShipInfo'))

		return $returnValue;
	} // END public function checkPluginDependencies($plugin)
} // END class PluginHelper
