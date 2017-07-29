<?php
namespace WordPress\Plugin\EveOnlineFittingManager\Libs;

use WordPress\Plugin\EveOnlineFittingManager;

\defined('ABSPATH') or die();

/**
 * Registering the Killboard Database as its own instance of wpdb
 */
class Database {
	private $pluginSettings = null;
	private static $instance;

	public $db = null;

	/**
	 * Constructor
	 */
	private function __construct() {
		$this->pluginSettings = \get_option(EveOnlineFittingManager\Helper\PluginHelper::getOptionFieldName(), EveOnlineFittingManager\Helper\PluginHelper::getPluginDefaultSettings());

		$this->db = $this->initiateKillboardDatabase();
	} // END private function __construct()

	/**
	 * Getting the Instance of this class
	 *
	 * @return object Class Instance
	 */
	public static function getInstance() {
		if(\is_null(self::$instance)) {
			self::$instance = new self();
		} // END if(\is_null(self::$instance))

		return self::$instance;
	} // END public static function getInstance()

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
