<?php
namespace WordPress\Plugin\EveOnlineFittingManager\Libs\Backend;

use WordPress\Plugin\EveOnlineFittingManager;

\defined('ABSPATH') or die();

class PluginSettings {
	private $plugin = null;
	private $settingsFilter = null;
	private $defaultOptions = null;

	public function __construct() {
		$this->plugin = new EveOnlineFittingManager\EveOnlineFittingManager;
		$this->settingsFilter = 'register_eve_online_fittings_manager_settings';
		$this->defaultOptions = $this->plugin->getDefaultSettings();

		$this->fireSettingsApi();
	}

	public function fireSettingsApi() {
		$settingsApi = new SettingsApi($this->settingsFilter, $this->defaultOptions);
		$settingsApi->init();

		\add_filter($this->settingsFilter, array($this, 'getSettings'));
	} // END function fireSettingsApi()

	public function getSettings() {
		$themeOptionsPage['eve-online-fittings-manager'] = array(
			'type' => 'plugin',
			'menu_title' => \__('EVE Online Fittings Manager', 'eve-online-fitting-manager'),
			'page_title' => \__('EVE Online Fittings Manager', 'eve-online-fitting-manager'),
			'option_name' => $this->plugin->getOptionFieldName(), // Your settings name. With this name your settings are saved in the database.
			'tabs' => array(
				/**
				 * killboard settings tab
				 */
				'general-settings' => $this->getKillboardSettings(),
			)
		);

		return $themeOptionsPage;
	} // END function renderSettingsPage()

	private function getKillboardSettings() {
		return array(
			'tab_title' => \__('Killboard Settings', 'eve-online-fitting-manager'),
			'tab_description' => \__('You need to have a connection to a EDK killboard database in order to use this plugin. The database is needed to gather all the ship and item information.', 'eve-online-fitting-manager'),
			'fields' => $this->getKillboardSettingsFields()
		);
	} // END private function getKillboardSettings()

	private function getKillboardSettingsFields() {
		return array(
			'edk-killboard-host' => array(
				'type' => 'text',
				'title' => \__('DB Host', 'eve-online-fitting-manager'),
				'default' => 'localhost'
			),
			'edk-killboard-name' => array(
				'type' => 'text',
				'title' => \__('DB Name', 'eve-online-fitting-manager'),
			),
			'edk-killboard-user' => array(
				'type' => 'text',
				'title' => \__('DB User', 'eve-online-fitting-manager'),
			),
			'edk-killboard-password' => array(
				'type' => 'password',
				'title' => \__('DB Password', 'eve-online-fitting-manager'),
			)
		);
	} // END private function getKillboardSettingsFields()
} // END class PluginSettings