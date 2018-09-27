<?php

/**
 * Copyright (C) 2017 Rounon Dax
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace WordPress\Plugin\EveOnlineFittingManager\Libs\Helper;

\defined('ABSPATH') or die();

class MarketDataHelper extends \WordPress\Plugin\EveOnlineFittingManager\Libs\Singletons\AbstractSingleton {
    /**
     * Available Market APIs:
     *      EVE Central => https://api.eve-central.com/api/marketstat/json?typeid=3057,2364,3057&regionlimit=10000002&usesystem=30000142
     *      EVE Marketer => https://api.evemarketer.com/ec/marketstat/json?typeid=3057,2364,3057&regionlimit=10000002&usesystem=30000142
     */

    /**
     * EVE Central API Url
     *
     * @var string API Url
     */
    protected $apiUrlEveCentral = 'https://api.eve-central.com/api/marketstat/json';

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

        $this->pluginSettings = PluginHelper::getPluginSettings();
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
             * EVE Central
             */
            case 'eve-central':
                $this->apiUrl = $this->apiUrlEveCentral . $urlParameters;
                break;

            /**
             * EVE Marketer
             */
            case 'eve-marketer':
                $this->apiUrl = $this->apiUrlEveMarketer . $urlParameters;
                break;

            /**
             * Default: EVE Central
             * (If for whatever reason none is set in plugin settings)
             */
            default:
                $this->apiUrl = $this->apiUrlEveCentral . $urlParameters;
                break;
        }
    }

    /**
     * Getting the marketdata json
     *
     * @param array $items
     * @return string json string of all item marketdata
     */
    public function getMarketDataJson(array $items) {
        $typeIdString = \implode(',', $items);
        $transientName = 'eve_fitting_tool_' . $this->pluginSettings['market-data-api'] . '-market-data_fitting_' . \md5($typeIdString);
        $returnValue = CacheHelper::getInstance()->checkTransientCache($transientName);

        if($returnValue === false) {
//            $get = \wp_remote_get($this->apiUrl . $typeIdString);
            $get = $this->remoteHelper->getRemoteData($this->apiUrl . $typeIdString);
            $json = \wp_remote_retrieve_body($get);

            CacheHelper::getInstance()->setTransientCache($transientName, $json, 1);

            $returnValue = $json;
        }

        return $returnValue;
    }

    /**
     * Getting the market prices for our fitting ...
     *
     * @param array $fittingArray EFT fitting array from WordPress\Plugin\EveOnlineFittingManager\Helper\EftHelper::getFittingArrayFromEftData($eftFitting);
     * @return array Sell and Buy order prices from Jita
     */
    public function getMarketPricesFromFittingArray(array $fittingArray) {
        $returnValue = false;
        $jitaBuyPrice = 0;
        $jitaSellPrice = 0;

        // Ship price
        $ship = [
            $fittingArray['0']->itemID
        ];

        // Remove the ship from the array
        unset($fittingArray['0']);

        $marketJsonShip = $this->getMarketDataJson($ship);
        if($marketJsonShip !== false) {
            $marketArrayShip = \json_decode($marketJsonShip);

            if($marketArrayShip !== null) {
                $jitaBuyPrice = [
                    'ship' => $marketArrayShip['0']->buy->median,
                    'total' => $marketArrayShip['0']->buy->median
                ];

                $jitaSellPrice = [
                    'ship' => $marketArrayShip['0']->sell->median,
                    'total' => $marketArrayShip['0']->sell->median
                ];
            }
        }

        // Fitting Price
        $items = null;

        if(\is_array($fittingArray)) {
            foreach($fittingArray as $item) {
                $items[] = $item->itemID;
            }
        }

        // if we have items
        if($items !== null) {
            $marketJsonFitting = $this->getMarketDataJson($items);

            // If we have the json data
            if($marketJsonFitting !== false) {
                $marketArray = \json_decode($marketJsonFitting);
                $jitaBuyPrice['fitting'] = 0;
                $jitaSellPrice['fitting'] = 0;

                if($marketArray !== null) {
                    foreach($marketArray as $item) {
                        $jitaBuyPrice['fitting'] += $item->buy->median;
                        $jitaSellPrice['fitting'] += $item->sell->median;
                        $jitaBuyPrice['total'] += $item->buy->median;
                        $jitaSellPrice['total'] += $item->sell->median;
                    }
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
