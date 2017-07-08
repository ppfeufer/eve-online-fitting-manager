<?php


namespace WordPress\Plugin\EveOnlineFittingManager\Helper;

use WordPress\Plugin\EveOnlineFittingManager;

\defined('ABSPATH') or die();

class MarketdataHelper {
	private static $instance = null;

	// https://api.evemarketer.com/ec/marketstat/json?typeid=3057,2364,3057&regionlimit=10000002&usesystem=30000142
	public $apiUrl = 'https://api.evemarketer.com/ec/marketstat/json';
	public $marketRegion = '10000002'; // The Forge
	public $marketSystem = '30000142'; // Jita

	private function __construct() {
//		$this->apiUrl = $this->apiUrl . '?regionlimit=' . $this->marketRegion . '&usesystem=' . $this->marketSystem . '&typeid=';
		$this->apiUrl = $this->apiUrl . '?typeid='; // For now we use te API without system or region limit
	}

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
		$returnValue = false;

		$typeIdString = implode(',', $items);
		$transientName = 'eve_fitting_tool_market_data-' . md5($typeIdString);

		$cache = CacheHelper::getInstance()->checkTransientCache($transientName);

		if($cache === false) {
			$get = \wp_remote_get($this->apiUrl . $typeIdString);
			$json = \wp_remote_retrieve_body($get);

			CacheHelper::getInstance()->setTransientCache($transientName, $json, 1);

			$returnValue = $json;
		} else {
			$returnValue = $cache;
		}

		return $returnValue;
	}

	/**
	 * Getting the market prices for our fitting ...
	 *
	 * @param array $items Item fitted and in Cargo
	 * @return array Sell and Buy order prices from Jita
	 */
	public function getMarketPrices(Array $eftFitting) {
		$returnValue = false;

		$items = null;
		foreach($eftFitting as $item) {
			$items[] = $item->itemID;
		}

		$marketJson = $this->getMarketDataJson($items);

		if($marketJson !== false) {
			$marketArray = json_decode($marketJson);

			$jitaBuyPrice = null;
			$jitaSellPrice = null;

			foreach($marketArray as $item) {
				$jitaBuyPrice += $item->buy->median;
				$jitaSellPrice += $item->sell->median;
			}

			$returnValue = array(
				'jitaBuyPrice' => number_format($jitaBuyPrice, 2, ',', '.') . ' ISK',
				'jitaSellPrice' => number_format($jitaSellPrice, 2, ',', '.') . ' ISK'
			);
		}

		return $returnValue;
	}
}
