<?php

namespace WordPress\Plugin\EveOnlineFittingManager\Libs;

use WordPress\Plugin\EveOnlineFittingManager;

\defined('ABSPATH') or die();

/**
 * Registering the Killboard Database as its own instance of wpdb
 */
class MarketData {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->initActions();
	}

	/**
	 * Initialize the actions
	 */
	private function initActions() {
		\add_action('wp_ajax_nopriv_get-eve-fitting-market-data', array($this, 'ajaxGetFittingMarketData'));
		\add_action('wp_ajax_get-eve-fitting-market-data', array($this, 'ajaxGetFittingMarketData'));
	}

	/**
	 * Getting the market data for a fitting
	 */
	public function ajaxGetFittingMarketData() {
		$eftFitting = \filter_input(\INPUT_POST, 'eftData');
		$fittingArray = \WordPress\Plugin\EveOnlineFittingManager\Helper\EftHelper::getFittingArrayFromEftData($eftFitting);
		$marketPrices = \WordPress\Plugin\EveOnlineFittingManager\Helper\MarketDataHelper::getInstance()->getMarketPricesFromFittingArray($fittingArray);

		echo \json_encode($marketPrices);

		// always exit this API function
		exit;
	}
}
