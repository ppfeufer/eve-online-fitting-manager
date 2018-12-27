<?php

/*
 * Copyright (C) 2017 ppfeufer
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace WordPress\Plugins\EveOnlineFittingManager\Libs\Helper;

use WordPress\Plugins\EveOnlineFittingManager\Libs\Singletons\AbstractSingleton;

\defined('ABSPATH') or die();

class MarketDataHelper extends AbstractSingleton {
    /**
     * Available Market APIs:
     *      EVE Marketer => https://api.evemarketer.com/ec/marketstat/json?typeid=3057,2364,3057&regionlimit=10000002&usesystem=30000142
     */

    /**
     * EVE Marketer API Url
     *
     * @var string API Url
     */
    protected $apiUrlEveMarketer = 'https://api.evemarketer.com/ec/marketstat/json';

    /**
     * API Url
     *
     * @var string API Url to use
     */
    protected $apiUrl = null;

    /**
     * Market Region Limiter
     *
     * @var int Market Region to use
     */
    protected $marketRegion = 10000002; // The Forge

    /**
     * Market System Limiter
     *
     * @var int Market System to use
     */
    protected $marketSystem = 30000142; // Jita

    /**
     * Plugin Settings
     *
     * @var array
     */
    protected $pluginSettings = null;

    /**
     *
     * @var RemoteHelper
     */
    protected $remoteHelper = null;

    /**
     * Constructor
     */
    protected function __construct() {
        parent::__construct();

        $this->pluginSettings = PluginHelper::getInstance()->getPluginSettings();
        $this->remoteHelper = RemoteHelper::getInstance();

        $this->setMarketApi();
    }

    /**
     * Set the market API that is to be used
     */
    public function setMarketApi() {
        $urlParameters = '?regionlimit=' . $this->marketRegion . '&usesystem=' . $this->marketSystem . '&typeid=';

        switch($this->pluginSettings['market-data-api']) {
            /**
             * EVE Marketer
             */
            case 'eve-marketer':
                $this->apiUrl = $this->apiUrlEveMarketer . $urlParameters;
                break;

            /**
             * Default: EVE Marketer
             * (If for whatever reason none is set in plugin settings)
             */
            default:
                $this->apiUrl = $this->apiUrlEveMarketer . $urlParameters;
                break;
        }
    }

    /**
     * Getting the marketdata json
     *
     * @param array $items
     * @return string json string of all item marketdata
     */
    public function getMarketData(array $items) {
        $typeIdString = \implode(',', $items);
        $cacheKey = $this->pluginSettings['market-data-api'] . '/' . \md5($typeIdString);

        $marketData = DatabaseHelper::getInstance()->getMarketDataCache($cacheKey);

        if(\is_null($marketData)) {
            $marketData = $this->remoteHelper->getRemoteData($this->apiUrl . $typeIdString);

            DatabaseHelper::getInstance()->writeMarketDataCache([
                $cacheKey,
                \maybe_serialize($marketData),
                \strtotime('+1 hour')
            ]);
        }

        return $marketData;
    }

    /**
     * Getting the market prices for our fitting ...
     *
     * @param array $fittingArray EFT fitting array from WordPress\Plugins\EveOnlineFittingManager\Helper\EftHelper::getFittingArrayFromEftData($eftFitting);
     * @return array Sell and Buy order prices from Jita
     */
    public function getMarketPricesFromFittingArray(array $fittingArray) {
        $returnValue = false;
        $jitaBuyPrice = [];
        $jitaSellPrice = [];

        // Ship price
        $ship = [
            $fittingArray['0']->itemID
        ];

        // Remove the ship from the array
        unset($fittingArray['0']);

        $marketDataShip = $this->getMarketData($ship);

        if(!\is_null($marketDataShip)) {
            $jitaBuyPrice = [
                'ship' => $marketDataShip['0']->buy->median,
                'total' => $marketDataShip['0']->buy->median
            ];

            $jitaSellPrice = [
                'ship' => $marketDataShip['0']->sell->median,
                'total' => $marketDataShip['0']->sell->median
            ];
        }

        // Fitting Price
        $items = null;

        if(\is_array($fittingArray)) {
            foreach($fittingArray as $item) {
                $items[] = $item->itemID;
            }
        }

        // if we have items
        if(!\is_null($items)) {
            $marketDataFitting = $this->getMarketData($items);

            if(!\is_null($marketDataFitting)) {
                $jitaBuyPrice['fitting'] = 0;
                $jitaSellPrice['fitting'] = 0;

                foreach($marketDataFitting as $item) {
                    $jitaBuyPrice['fitting'] += $item->buy->median;
                    $jitaSellPrice['fitting'] += $item->sell->median;
                    $jitaBuyPrice['total'] += $item->buy->median;
                    $jitaSellPrice['total'] += $item->sell->median;
                }

                $returnValue = [
                    'ship' => [
                        'jitaBuyPrice' => \number_format($jitaBuyPrice['ship'], 2, ',', '.') . ' ISK',
                        'jitaSellPrice' => \number_format($jitaSellPrice['ship'], 2, ',', '.') . ' ISK'
                    ],
                    'fitting' => [
                        'jitaBuyPrice' => \number_format($jitaBuyPrice['fitting'], 2, ',', '.') . ' ISK',
                        'jitaSellPrice' => \number_format($jitaSellPrice['fitting'], 2, ',', '.') . ' ISK'
                    ],
                    'total' => [
                        'jitaBuyPrice' => \number_format($jitaBuyPrice['total'], 2, ',', '.') . ' ISK',
                        'jitaSellPrice' => \number_format($jitaSellPrice['total'], 2, ',', '.') . ' ISK'
                    ]
                ];
            }
        }

        return $returnValue;
    }
}
