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

use \WordPress\Plugins\EveOnlineFittingManager\Libs\Singletons\AbstractSingleton;

\defined('ABSPATH') or die();

class ImageHelper extends AbstractSingleton {
    /**
     * base URL to CCP's image server
     *
     * @var var
     */
    protected $imageserverUrl = 'https://imageserver.eveonline.com/';

    /**
     * Array with possible end point on CCP's image server
     *
     * @var array
     */
    protected $imageserverEndpoints = null;

    /**
     * The Construtor
     */
    protected function __construct() {
        parent::__construct();

        $this->imageserverEndpoints = [
            'alliance' => 'Alliance/',
            'corporation' => 'Corporation/',
            'character' => 'Character/',
            'item' => 'Type/',
            'ship' => 'Render/',
            'inventory' => 'InventoryType/' // all the other stuff
        ];
    }

    /**
     * Assigning Imagesever Endpoints
     */
    public function getImageserverEndpoints() {
        return $this->imageserverEndpoints;
    }

    /**
     * Getting the EVE Imageserver Url
     *
     * @param string $type
     * @return string
     */
    public function getImageServerUrl($type = null) {
        $endpoint = '';

        if($type !== null) {
            $endpoint = $this->imageserverEndpoints[$type];
        }

        return $this->imageserverUrl . $endpoint;
    }
}
