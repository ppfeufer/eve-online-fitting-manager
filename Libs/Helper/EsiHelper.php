<?php

/**
 * Copyright (C) 2017 Rounon Dax
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

/**
 * EVE API Helper
 *
 * Getting some stuff from CCP's EVE API
 */
namespace WordPress\Plugins\EveOnlineFittingManager\Libs\Helper;

use \WordPress\EsiClient\Model\Universe\UniverseGroupsGroupId;
use \WordPress\EsiClient\Model\Universe\UniverseIds;
use \WordPress\EsiClient\Model\Universe\UniverseTypesTypeId;
use \WordPress\EsiClient\Repository\AllianceRepository;
use \WordPress\EsiClient\Repository\CharacterRepository;
use \WordPress\EsiClient\Repository\CorporationRepository;
use \WordPress\EsiClient\Repository\UniverseRepository;
use \WordPress\Plugins\EveOnlineFittingManager\Libs\Singletons\AbstractSingleton;

\defined('ABSPATH') or die();

class EsiHelper extends AbstractSingleton {
    /**
     * Image Server URL
     *
     * @var string
     */
    private $imageserverUrl = null;

    /**
     * Image Server Endpoints
     *
     * @var array
     */
    private $imageserverEndpoints = null;

    /**
     * Plugin Helper
     *
     * @var \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\ImageHelper
     */
    private $imageHelper = null;

    /**
     * Plugin Helper
     *
     * @var \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\PluginHelper
     */
    private $pluginHelper = null;

    /**
     * Plugin Settings
     *
     * @var array
     */
    private $pluginSettings = null;

    /**
     * Cache Helper
     *
     * @var \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\CacheHelper
     */
    private $cacheHelper = null;

    /**
     * Remote Helper
     *
     * @var \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\RemoteHelper
     */
    private $remoteHelper = null;

    /**
     * Database Helper
     *
     * @var \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\DatabaseHelper
     */
    private $databaseHelper = null;

    /**
     * ESI Character API
     *
     * @var CharacterRepository
     */
    private $characterApi = null;

    /**
     * ESI Corporation API
     *
     * @var CorporationRepository
     */
    private $corporationApi = null;

    /**
     * ESI Alliance API
     *
     * @var AllianceRepository
     */
    private $allianceApi = null;

    /**
     * ESI Universe API
     *
     * @var UniverseRepository
     */
    private $universeApi = null;

    /**
     * The Constructor
     */
    protected function __construct() {
        parent::__construct();

        if(!$this->pluginHelper instanceof \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\PluginHelper) {
            $this->pluginHelper = PluginHelper::getInstance();
            $this->pluginSettings = $this->pluginHelper->getPluginSettings();
        }

        if(!$this->imageHelper instanceof \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\ImageHelper) {
            $this->imageHelper = ImageHelper::getInstance();
            $this->imageserverEndpoints = $this->imageHelper->getImageserverEndpoints();
            $this->imageserverUrl = $this->imageHelper->getImageServerUrl();
        }

        $this->databaseHelper = DatabaseHelper::getInstance();
        $this->cacheHelper = CacheHelper::getInstance();
        $this->remoteHelper = RemoteHelper::getInstance();

        /**
         * ESI API Client
         */
        $this->characterApi = new CharacterRepository;
        $this->corporationApi = new CorporationRepository;
        $this->allianceApi = new AllianceRepository;
        $this->universeApi = new UniverseRepository;
    }

    /**
     * Getting all the needed ship information from the ESI
     *
     * @param int $shipId
     * @return array
     */
    public function getShipData($shipId) {
        $returnData = null;

        /* @var $shipClassData UniverseTypesTypeId */
        $shipClassData = $this->getShipClassDataFromShipId($shipId);

        $shipTypeData = null;

        if(!\is_null($shipClassData->getGroupId())) {
            /* @var $shipTypeData UniverseGroupsGroupId */
            $shipTypeData = $this->getShipTypeDataFromShipClass($shipClassData);
        }

        if(!\is_null($shipClassData) && !\is_null($shipTypeData)) {
            $returnData = [
                'shipData' => $shipClassData,
                'shipTypeData' => $shipTypeData
            ];
        }

        return $returnData;
    }

    /**
     * Getting ship class data by ship id
     *
     * @param int $shipId
     * @return UniverseTypesTypeId
     */
    public function getShipClassDataFromShipId($shipId) {
        /* @var $shipClassData UniverseTypesTypeId */
        $shipClassData = $this->databaseHelper->getCachedEsiDataFromDb('universe/types/' . $shipId);

        if(\is_null($shipClassData)) {
            $shipClassData = $this->universeApi->universeTypesTypeId($shipId);

            $this->databaseHelper->writeEsiCacheDataToDb([
                'universe/types/' . $shipId,
                \maybe_serialize($shipClassData),
                \strtotime('+1 week')
            ]);
        }

        return $shipClassData;
    }

    /**
     * Get ship type data by ship class
     *
     * @param UniverseTypesTypeId $shipData
     * @return UniverseGroupsGroupId
     */
    public function getShipTypeDataFromShipClass(UniverseTypesTypeId $shipData) {
        /* @var $shipTypeData UniverseGroupsGroupId */
        $shipTypeData = $this->databaseHelper->getCachedEsiDataFromDb('universe/groups/' . $shipData->getGroupId());

        if(\is_null($shipTypeData)) {
            $shipTypeData = $this->universeApi->universeGroupsGroupId($shipData->getGroupId());

            $this->databaseHelper->writeEsiCacheDataToDb([
                'universe/groups/' . $shipData->getGroupId(),
                \maybe_serialize($shipTypeData),
                \strtotime('+1 week')
            ]);
        }

        return $shipTypeData;
    }

    /**
     * Get the affiliation for a set of characterIDs
     *
     * @param array $characterIds
     * @return array
     */
    public function getCharacterAffiliation(array $characterIds) {
        $characterAffiliationData = $this->characterApi->charactersAffiliation(\array_values($characterIds));

        return $characterAffiliationData;
    }

    /**
     * Get the IDs to an array of names
     *
     * @param array $names
     * @param string $type
     * @return type
     */
    public function getIdFromName(array $names, $type) {
        $returnData = null;

        /* @var $esiData UniverseIds */
        $esiData = $this->universeApi->universeIds(\array_values($names));

        switch($type) {
            case 'agents':
                $returnData = $esiData->getAgents();
                break;

            case 'alliances':
                $returnData = $esiData->getAlliances();
                break;

            case 'constellations':
                $returnData = $esiData->getConstellations();
                break;

            case 'characters':
                $returnData = $esiData->getCharacters();
                break;

            case 'corporations':
                $returnData = $esiData->getCorporations();
                break;

            case 'factions':
                $returnData = $esiData->getFactions();
                break;

            case 'inventoryTypes':
                $returnData = $esiData->getInventoryTypes();
                break;

            case 'regions':
                $returnData = $esiData->getRegions();
                break;

            case 'stations':
                $returnData = $esiData->getStations();
                break;

            case 'systems':
                $returnData = $esiData->getSystems();
                break;
        }

        return $returnData;
    }

    /**
     * Get corporation data by ID
     *
     * @param string $corporationId
     * @return object
     */
    public function getCorporationData($corporationId) {
        $corporationData = $this->databaseHelper->getCachedEsiDataFromDb('corporations/' . $corporationId);

        if(\is_null($corporationData)) {
            $corporationData = $this->corporationApi->corporationsCorporationId($corporationId);

            if(!\is_null($corporationData)) {
                $this->databaseHelper->writeEsiCacheDataToDb([
                    'corporations/' . $corporationId,
                    \maybe_serialize($corporationData),
                    \strtotime('+1 week')
                ]);
            }
        }

        return $corporationData;
    }

    /**
     * Get alliance data by ID
     *
     * @global object $wpdb
     * @param string $allianceId
     * @return object
     */
    public function getAllianceData($allianceId) {
        $allianceData = $this->databaseHelper->getCachedEsiDataFromDb('alliances/' . $allianceId);

        if(\is_null($allianceData)) {
            $allianceData = $this->allianceApi->alliancesAllianceId($allianceId);

            if(!\is_null($allianceData)) {
                $this->databaseHelper->writeEsiCacheDataToDb([
                    'alliances/' . $allianceId,
                    \maybe_serialize($allianceData),
                    \strtotime('+1 week')
                ]);
            }
        }

        return $allianceData;
    }

    /**
     * Getting all the needed system information from the ESI
     *
     * @param int $systemId
     * @return array
     */
    public function getSystemData($systemId) {
        $systemData = $this->databaseHelper->getCachedEsiDataFromDb('universe/systems/' . $systemId);

        if(\is_null($systemData)) {
            $systemData = $this->universeApi->universeSystemsSystemId($systemId);

            if(!\is_null($systemData)) {
                $this->databaseHelper->writeEsiCacheDataToDb([
                    'universe/systems/' . $systemId,
                    \maybe_serialize($systemData),
                    \strtotime('+10 years')
                ]);
            }
        }

        return $systemData;
    }

    /**
     * Getting all the needed constellation information from the ESI
     *
     * @param int $constellationId
     * @return array
     */
    public function getConstellationData($constellationId) {
        $constellationData = $this->databaseHelper->getCachedEsiDataFromDb('universe/constellations/' . $constellationId);

        if(\is_null($constellationData)) {
            $constellationData = $this->universeApi->universeConstellationsConstellationId($constellationId);

            if(!\is_null($constellationData)) {
                $this->databaseHelper->writeEsiCacheDataToDb([
                    'universe/constellations/' . $constellationId,
                    \maybe_serialize($constellationData),
                    \strtotime('+10 years')
                ]);
            }
        }

        return $constellationData;
    }

    /**
     * Getting all the needed constellation information from the ESI
     *
     * @param int $regionId
     * @return array
     */
    public function getRegionData($regionId) {
        $regionData = $this->databaseHelper->getCachedEsiDataFromDb('universe/regions/' . $regionId);

        if(\is_null($regionData)) {
            $regionData = $this->universeApi->universeRegionsRegionId($regionId);

            if(!\is_null($regionData)) {
                $this->databaseHelper->writeEsiCacheDataToDb([
                    'universe/regions/' . $regionId,
                    \maybe_serialize($regionData),
                    \strtotime('+10 years')
                ]);
            }
        }

        return $regionData;
    }
}
