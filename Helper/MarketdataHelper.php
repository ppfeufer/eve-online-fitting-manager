<?php

namespace WordPress\Plugin\EveOnlineFittingManager\Helper;

use WordPress\Plugin\EveOnlineFittingManager;

\defined('ABSPATH') or die();

class MarketdataHelper {
	/**
	 * Instance
	 *
	 * @var object The current instance
	 */
	private static $instance = null;

	/**
	 * Available Market APIs:
	 *		EVE Central => https://api.eve-central.com/api/marketstat/json?typeid=3057,2364,3057&regionlimit=10000002&usesystem=30000142
	 *		EVE Marketer => https://api.evemarketer.com/ec/marketstat/json?typeid=3057,2364,3057&regionlimit=10000002&usesystem=30000142
	 */

	/**
	 * EVE Central API Url
	 *
	 * @var string API Url
	 */
	public $apiUrlEveCentral = 'https://api.eve-central.com/api/marketstat/json';

	/**
	 *	EVE Marketer API Url
	 *
	 * @var string API Url
	 */
	public $apiUrlEveMarketer = 'https://api.evemarketer.com/ec/marketstat/json';

	/**
	 * API Url
	 *
	 * @var string API Url to use
	 */
	public $apiUrl =  null;

	/**
	 * Market Region Limiter
	 *
	 * @var int Market Region to use
	 */
	public $marketRegion = 10000002; // The Forge

	/**
	 * Market System Limiter
	 *
	 * @var int Market System to use
	 */
	public $marketSystem = 30000142; // Jita

	/**
	 * Constructor
	 */
	private function __construct() {
//		$this->apiUrl = $this->apiUrlEveMarketer . '?regionlimit=' . $this->marketRegion . '&usesystem=' . $this->marketSystem . '&typeid=';
		$this->apiUrl = $this->apiUrlEveCentral . '?typeid=';
	}

	/**
	 * Getting the instance
	 *
	 * @return WordPress\Plugin\EveOnlineFittingManager\Helper\MarketdataHelper Instance
	 */
	public static function getInstance() {
		if(\is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Getting the marketdata json
	 *
	 * @param array $items
	 * @return string json string of all item marketdata
	 */
	public function getMarketDataJson(Array $items) {
		$typeIdString = \implode(',', $items);
		$transientName = 'eve_fitting_tool_market_data_fitting_' . \md5($typeIdString);
		$returnValue = CacheHelper::getInstance()->checkTransientCache($transientName);

		if($returnValue === false) {
			$get = \wp_remote_get($this->apiUrl . $typeIdString);
			$json = \wp_remote_retrieve_body($get);

			CacheHelper::getInstance()->setTransientCache($transientName, $json, 1);

			$returnValue = $json;
		} // END if($returnValue === false)

		return $returnValue;
	} // END public function getMarketDataJson(Array $items)

	/**
	 * Getting the market prices for our fitting ...
	 *
	 * @param array $fittingArray EFT fitting array from WordPress\Plugin\EveOnlineFittingManager\Helper\EftHelper::getFittingArrayFromEftData($eftFitting);
	 * @return array Sell and Buy order prices from Jita
	 */
	public function getMarketPricesFromFittingArray(Array $fittingArray) {
		$returnValue = false;
		$jitaBuyPrice = null;
		$jitaSellPrice = null;

		// Ship price
		$ship = array(
			$fittingArray['0']->itemID
		);

		// Remove the ship from the array
		unset($fittingArray['0']);

		$marketJsonShip = $this->getMarketDataJson($ship);
		if($marketJsonShip !== false) {
			$marketArrayShip = \json_decode($marketJsonShip);

			$jitaBuyPrice = array(
				'ship' => $marketArrayShip['0']->buy->median,
				'total' => $marketArrayShip['0']->buy->median
			);
			$jitaSellPrice = array(
				'ship' => $marketArrayShip['0']->sell->median,
				'total' => $marketArrayShip['0']->sell->median
			);
		}

		// Fitting Price
		$items = null;
		foreach($fittingArray as $item) {
			$items[] = $item->itemID;
		} // END foreach($fittingArray as $item)

		// if we have items
		if($items !== null) {
			$marketJsonFitting = $this->getMarketDataJson($items);

			// If we have the json data
			if($marketJsonFitting !== false) {
				$marketArray = \json_decode($marketJsonFitting);
				$jitaBuyPrice['fitting'] = null;
				$jitaSellPrice['fitting'] = null;

				foreach($marketArray as $item) {
					$jitaBuyPrice['fitting'] += $item->buy->median;
					$jitaSellPrice['fitting'] += $item->sell->median;
					$jitaBuyPrice['total'] += $item->buy->median;
					$jitaSellPrice['total'] += $item->sell->median;
				} // END foreach($marketArray as $item) s

				$returnValue = array(
					'ship' => array(
						'jitaBuyPrice' => \number_format($jitaBuyPrice['ship'], 2, ',', '.') . ' ISK',
						'jitaSellPrice' => \number_format($jitaSellPrice['ship'], 2, ',', '.') . ' ISK'
					),
					'fitting' => array(
						'jitaBuyPrice' => \number_format($jitaBuyPrice['fitting'], 2, ',', '.') . ' ISK',
						'jitaSellPrice' => \number_format($jitaSellPrice['fitting'], 2, ',', '.') . ' ISK'
					),
					'total' => array(
						'jitaBuyPrice' => \number_format($jitaBuyPrice['total'], 2, ',', '.') . ' ISK',
						'jitaSellPrice' => \number_format($jitaSellPrice['total'], 2, ',', '.') . ' ISK'
					)
				);
			} // END if($marketJson !== false)
		} // END if($items !== null)

		return $returnValue;
	} // END public function getMarketPrices(Array $fittingArray)
} // END class MarketdataHelper
