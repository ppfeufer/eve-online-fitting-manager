<?php
namespace WordPress\Plugin\EveOnlineFittingManager\Libs;

use WordPress\Plugin\EveOnlineFittingManager;

\defined('ABSPATH') or die();

/**
 * Registering the Killboard Database as its own instance of wpdb
 */
class Database extends EveOnlineFittingManager\Singleton\AbstractSingleton {
	private $pluginSettings = null;

	public $db = null;

	/**
	 * Constructor
	 */
	protected function __construct() {
		parent::__construct();

		$this->pluginSettings = \get_option(EveOnlineFittingManager\Helper\PluginHelper::getOptionFieldName(), EveOnlineFittingManager\Helper\PluginHelper::getPluginDefaultSettings());

		$this->db = $this->initiateKillboardDatabase();
	} // END private function __construct()

	/**
	 * Initializing the Database
	 *
	 * @return \wpdb WordPress Database Object
	 */
	private function initiateKillboardDatabase() {
		$returnValue = false;

		if(!empty($this->pluginSettings['edk-killboard-user']) && !empty($this->pluginSettings['edk-killboard-password']) && !empty($this->pluginSettings['edk-killboard-name']) && !empty($this->pluginSettings['edk-killboard-host'])) {
			$returnValue = new \wpdb($this->pluginSettings['edk-killboard-user'], $this->pluginSettings['edk-killboard-password'], $this->pluginSettings['edk-killboard-name'], $this->pluginSettings['edk-killboard-host']);
		} // END if(!empty($this->pluginSettings['edk-killboard-user']) && !empty($this->pluginSettings['edk-killboard-password']) && !empty($this->pluginSettings['edk-killboard-name']) && !empty($this->pluginSettings['edk-killboard-host']))

		return $returnValue;
	} // END private function initiateKillboardDatabase()
} // END class Database
