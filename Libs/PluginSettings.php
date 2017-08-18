<?php
namespace WordPress\Plugin\EveOnlineFittingManager\Libs;

\defined('ABSPATH') or die();

/**
 * Registering the plugin settings
 */
class PluginSettings {
	private $settingsFilter = null;
	private $defaultOptions = null;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->settingsFilter = 'register_eve_online_fittings_manager_settings';
		$this->defaultOptions = Helper\PluginHelper::getPluginDefaultSettings();

		$this->fireSettingsApi();
	} // END public function __construct()

	/**
	 * Fire the Settings API
	 */
	public function fireSettingsApi() {
		$settingsApi = new SettingsApi($this->settingsFilter, $this->defaultOptions);
		$settingsApi->init();

		\add_filter($this->settingsFilter, [$this, 'getSettings']);
	} // END function fireSettingsApi()

	/**
	 * Getting the Settings for the PLugin Options Page
	 *
	 * @return array The Settings for the Options Page
	 */
	public function getSettings() {
		$pluginOptionsPage['eve-online-fittings-manager'] = [
			'type' => 'plugin',
			'menu_title' => \__('EVE Online Fittings Manager', 'eve-online-fitting-manager'),
			'page_title' => \__('EVE Online Fittings Manager', 'eve-online-fitting-manager'),
			'option_name' => Helper\PluginHelper::getOptionFieldName(), // Your settings name. With this name your settings are saved in the database.
			'tabs' => [
				/**
				 * killboard settings tab
				 */
				'general-settings' => $this->getKillboardSettings(),
				'template-settings' => $this->getTemplateSettings()
			]
		];

		return $pluginOptionsPage;
	} // END function renderSettingsPage()

	/**
	 * Getting the Killboard Databse Settings
	 *
	 * @return array The Killboard Database Setting
	 */
	private function getKillboardSettings() {
		$settings = [
			'tab_title' => \__('Killboard Settings', 'eve-online-fitting-manager'),
			'tab_description' => \__('You need to have a connection to a EDK killboard database in order to use this plugin. The database is needed to gather all the ship and item information.', 'eve-online-fitting-manager'),
			'fields' => $this->getKillboardSettingsFields()
		];

		return $settings;
	} // END private function getKillboardSettings()

	/**
	 * Getting the Template related settings
	 *
	 * @return array The Template Settings
	 */
	private function getTemplateSettings() {
		$settings = [
			'tab_title' => \__('Template Settings', 'eve-online-fitting-manager'),
			'fields' => $this->getTemplateSettingsFields()
		];

		return $settings;
	} // END private function getKillboardSettings()

	/**
	 * Get the settings fields for the Killboard settings
	 * @return array Settings fields for the Killboard settings
	 */
	private function getKillboardSettingsFields() {
//		$infotext = sprintf(
//			\__('If you don\'t have a local EDK killboard installation you can use, it is suggested to install and activate the %1$s plugin, so we can use this plugins database.', 'eve-online-fitting-manager'),
//			'<a href="http://aeonoftime.com/EVE_Online_Tools/EVE-ShipInfo-WordPress-Plugin/" target="_blank">' . \__('EVE ShipInfo', 'eve-online-fitting-manager') . '</a>'
//		);

//		if(EveOnlineFittingManager\Helper\PluginHelper::checkPluginDependencies('EVEShipInfo') === true) {
//			$infotext = \__('Since you already have the EVE ShipInfo Plugin installed and activated, there is no need for any other settings, we can use that plugins database straight away.', 'eve-online-fitting-manager');
//		} // END if(EveOnlineFittingManager\Helper\PluginHelper::checkPluginDependencies('EVEShipInfo') === true)

		$settingsFields = [
			'' => [
				'type' => 'info',
//				'infotext' => $infotext
			],
			'edk-killboard-host' => [
				'type' => 'text',
				'title' => \__('DB Host', 'eve-online-fitting-manager'),
				'default' => 'localhost'
			],
			'edk-killboard-name' => [
				'type' => 'text',
				'title' => \__('DB Name', 'eve-online-fitting-manager'),
			],
			'edk-killboard-user' => [
				'type' => 'text',
				'title' => \__('DB User', 'eve-online-fitting-manager'),
			],
			'edk-killboard-password' => [
				'type' => 'password',
				'title' => \__('DB Password', 'eve-online-fitting-manager'),
			]
		];

		return $settingsFields;
	} // END private function getKillboardSettingsFields()

	/**
	 * get the settings fields for the template related settings
	 *
	 * @return array Settings fields for the template related settings
	 */
	private function getTemplateSettingsFields() {
		$settingsFields = [
			'template-image-settings' => [
				'type' => 'checkbox',
				'title' => \__('Image Settings', 'eve-online-fitting-manager'),
				'choices' => [
					'show-ship-images-in-loop' => \__('Show ship images in ship list', 'eve-online-fitting-manager'),
					'show-doctrine-images-in-loop' => \sprintf(\__('Show doctrine images in doctrine list <small><em>(You need to have the %1$s plugin installed to make this happen)</em></small>', 'eve-online-fitting-manager'),
						'<a href="https://wordpress.org/plugins/categories-images/" target="_blank">' . \__('Categories Images', 'eve-online-fitting-manager') . '</a>'
					)
				],
			],
			'template-detail-parts-settings' => [
				'type' => 'checkbox',
				'title' => \__('Detail Page Settings', 'eve-online-fitting-manager'),
				'choices' => [
					'show-visual-fitting' => \__('Show visual fitting', 'eve-online-fitting-manager'),
					'show-ship-description' => \__('Show ship description', 'eve-online-fitting-manager'),
					'show-osmium-link' => \__('Show Osmium button', 'eve-online-fitting-manager'),
					'show-copy-eft' => \__('Show "Copy EFT data to clipboard" button', 'eve-online-fitting-manager'),
					'show-copy-permalink' => \__('Show "Copy permalink to clipboard" button', 'eve-online-fitting-manager'),
					'show-market-data' => \__('Show "Estimated Prices" section', 'eve-online-fitting-manager'),
					'show-doctrines' => \__('Show "Doctrines using this fitting" section', 'eve-online-fitting-manager')
				],
			]
		];

		return $settingsFields;
	} // END private function getKillboardSettingsFields()
} // END class PluginSettings
