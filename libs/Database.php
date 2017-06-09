<?php
namespace WordPress\Plugin\EveOnlineFittingManager\Libs;

use WordPress\Plugin\EveOnlineFittingManager;

\defined('ABSPATH') or die();

class Database {
	private $plugin = null;
	private $pluginSettings = null;
	private static $instance;

	public $db = null;

	private function __construct() {
		$this->plugin = new EveOnlineFittingManager\EveOnlineFittingManager;
		$this->pluginSettings = \get_option($this->plugin->getOptionFieldName(), $this->plugin->getDefaultSettings());

		$this->db = $this->initiateKillboardDatabase();
	}

	public static function getInstance() {
		if(\is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function initiateKillboardDatabase() {
		$returnValue = false;

		if(!empty($this->pluginSettings['edk-killboard-user']) && !empty($this->pluginSettings['edk-killboard-password']) && !empty($this->pluginSettings['edk-killboard-name']) && !empty($this->pluginSettings['edk-killboard-host'])) {
			$returnValue = new \wpdb($this->pluginSettings['edk-killboard-user'], $this->pluginSettings['edk-killboard-password'], $this->pluginSettings['edk-killboard-name'], $this->pluginSettings['edk-killboard-host']);
		} // END if(!empty($this->pluginSettings['edk-killboard-user']) && !empty($this->pluginSettings['edk-killboard-password']) && !empty($this->pluginSettings['edk-killboard-name']) && !empty($this->pluginSettings['edk-killboard-host']))

		return $returnValue;
	}
}
