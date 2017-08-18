<?php
/*
Copyright (C) 2016 Rounon Dax

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

namespace WordPress\Plugin\EveOnlineFittingManager\Libs\Helper;

\defined('ABSPATH') or die();

class EveApiHelper extends \WordPress\Plugin\EveOnlineFittingManager\Libs\Singletons\AbstractSingleton {
	public $imageserverUrl = 'https://image.eveonline.com/';
	public $imageserverEndpoints = null;

	/**
	 * The Construtor
	 */
	protected function __construct() {
		parent::__construct();

		$this->getImageserverEndpoints();
	} // END public function __construct()

	/**
	 * Assigning Imagesever Endpoints
	 */
	private function getImageserverEndpoints() {
		$this->imageserverEndpoints = [
			'alliance' => 'Alliance/',
			'corporation' => 'Corporation/',
			'character' => 'Character/',
			'item' => 'Type/',
			'ship' => 'Render/',
			'inventory' => 'InventoryType/' // all the other stuff
		];
	} // END private function setImageserverEndpoints()

	/**
	 * Getting the EVE API Url
	 *
	 * @param string $type
	 * @return string The EVE API Url
	 */
	public function getImageServerUrl($type = null) {
		$endpoint = '';

		if($type !== null) {
			$endpoint = $this->imageserverEndpoints[$type];
		} // END if($type !== null)

		return $this->imageserverUrl . $endpoint;
	} // END public function getImageServerUrl()
} // END class EveApi
