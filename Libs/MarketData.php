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

namespace WordPress\Plugins\EveOnlineFittingManager\Libs;

use \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\EftHelper;
use \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\MarketDataHelper;
use \WordPress\Plugins\EveOnlineFittingManager\Libs\Singletons\AbstractSingleton;

\defined('ABSPATH') or die();

/**
 * Registering the Killboard Database as its own instance of wpdb
 */
class MarketData extends AbstractSingleton {
    /**
     * Getting the market data for a fitting
     */
    public function ajaxGetFittingMarketData() {
        $eftFitting = \filter_input(\INPUT_POST, 'eftData');
        $fittingArray = EftHelper::getInstance()->getFittingArrayFromEftData($eftFitting);
        $marketPrices = MarketDataHelper::getInstance()->getMarketPricesFromFittingArray($fittingArray);

        \wp_send_json($marketPrices);

        // always exit this API function
        exit;
    }
}
