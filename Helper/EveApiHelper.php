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

namespace WordPress\Plugin\EveOnlineFittingManager\Helper;

use WordPress\Plugin\EveOnlineFittingManager;
use SimpleXMLElement;

\defined('ABSPATH') or die();

class EveApiHelper {
	public $imageserverUrl = 'https://image.eveonline.com/';
	public $imageserverEndpoints = null;

	private static $instance = null;

	/**
	 * The Construtor
	 */
	public function __construct() {
		$this->getImageserverEndpoints();
	} // END public function __construct()

	/**
	 * Getting the instance
	 *
	 * @return WordPress\Plugin\EveOnlineFittingManager\Helper\EveApiHelper
	 */
	public static function getInstance() {
		if(\is_null(self::$instance)) {
			self::$instance = new self();
		} // END if(\is_null(self::$instance))

		return self::$instance;
	} // END public static function getInstance()

	/**
	 * Assigning Imagesever Endpoints
	 */
	private function getImageserverEndpoints() {
		$this->imageserverEndpoints = array(
			'alliance' => 'Alliance/',
			'corporation' => 'Corporation/',
			'character' => 'Character/',
			'item' => 'Type/',
			'ship' => 'Render/',
			'inventory' => 'InventoryType/' // all the other stuff
		);
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
