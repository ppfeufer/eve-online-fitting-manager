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

use \WordPress\ {
    EsiClient\Model\Universe\UniverseGroupsGroupId,
    EsiClient\Model\Universe\UniverseIds,
    EsiClient\Model\Universe\UniverseTypesTypeId,
    EsiClient\Repository\DogmaRepository,
    EsiClient\Repository\UniverseRepository,
    Plugins\EveOnlineFittingManager\Libs\Singletons\AbstractSingleton
};

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
     * @var ImageHelper
     */
    private $imageHelper = null;

    /**
     * Plugin Helper
     *
     * @var PluginHelper
     */
    private $pluginHelper = null;

    /**
     * Plugin Settings
     *
     * @var array
     */
    private $pluginSettings = null;

    /**
     * Remote Helper
     *
     * @var RemoteHelper
     */
    private $remoteHelper = null;

    /**
     * Database Helper
     *
     * @var DatabaseHelper
     */
    private $databaseHelper = null;

    /**
     * ESI Universe API
     *
     * @var UniverseRepository
     */
    private $universeApi = null;

    /**
     * ESI Universe API
     *
     * @var DogmaRepository
     */
    private $dogmaApi = null;

    /**
     * The Constructor
     */
    protected function __construct() {
        parent::__construct();

        if(!$this->pluginHelper instanceof PluginHelper) {
            $this->pluginHelper = PluginHelper::getInstance();
            $this->pluginSettings = $this->pluginHelper->getPluginSettings();
        }

        if(!$this->imageHelper instanceof ImageHelper) {
            $this->imageHelper = ImageHelper::getInstance();
            $this->imageserverEndpoints = $this->imageHelper->getImageserverEndpoints();
            $this->imageserverUrl = $this->imageHelper->getImageServerUrl();
        }

        $this->databaseHelper = DatabaseHelper::getInstance();
        $this->remoteHelper = RemoteHelper::getInstance();

        /**
         * ESI API Client
         */
        $this->universeApi = new UniverseRepository;
        $this->dogmaApi = new DogmaRepository;
    }

    /**
     * Getting all the needed ship information from the ESI
     *
     * @param int $itemId
     * @return array
     */
    public function getItemDataByItemId($itemId) {
        $returnData = [
            'itemTypeInformation' => null,
            'itemGroupInformation' => null,
            'itemCategoryInformation' => null
        ];

        /* @var $itemTypeInformation UniverseTypesTypeId */
        $itemTypeInformation = $this->getItemTypeInformation($itemId);

        $itemGroupInformation = null;
        $itemCategoryInformation = null;

        if(!\is_null($itemTypeInformation)) {
            $returnData['itemTypeInformation'] = $itemTypeInformation;

            /* @var $itemGroupInformation UniverseGroupsGroupId */
            $itemGroupInformation = $this->getItemGroupInformation($itemTypeInformation->getGroupId());

            if(!\is_null($itemGroupInformation)) {
                $returnData['itemGroupInformation'] = $itemGroupInformation;

                $itemCategoryInformation = $this->getItemCategoryInformation($itemGroupInformation->getCategoryId());

                $returnData['itemCategoryInformation'] = $itemCategoryInformation;
            }
        }

        return $returnData;
    }

    /**
     * Gettingitem data
     *
     * @param int $itemId
     * @return UniverseTypesTypeId
     */
    public function getItemTypeInformation(int $itemId) {
        /* @var $itemTypeInformation UniverseTypesTypeId */
        $itemTypeInformation = $this->databaseHelper->getCachedEsiDataFromDb('universe/types/' . $itemId);

        if(\is_null($itemTypeInformation)) {
            $itemTypeInformation = $this->universeApi->universeTypesTypeId($itemId);

            $this->databaseHelper->writeEsiCacheDataToDb([
                'universe/types/' . $itemId,
                \maybe_serialize($itemTypeInformation),
                \strtotime('+1 week')
            ]);
        }

        return $itemTypeInformation;
    }

    /**
     * Get item group data
     *
     * @param int $groupId
     * @return UniverseGroupsGroupId
     */
    public function getItemGroupInformation(int $groupId) {
        /* @var $groupData UniverseGroupsGroupId */
        $groupData = $this->databaseHelper->getCachedEsiDataFromDb('universe/groups/' . $groupId);

        if(\is_null($groupData)) {
            $groupData = $this->universeApi->universeGroupsGroupId($groupId);

            $this->databaseHelper->writeEsiCacheDataToDb([
                'universe/groups/' . $groupId,
                \maybe_serialize($groupData),
                \strtotime('+1 week')
            ]);
        }

        return $groupData;
    }

    /**
     * Get item category data
     *
     * @param int $categoryId
     * @return Universe
     */
    public function getItemCategoryInformation(int $categoryId) {
        /* @var $categoryData UniverseGroupsGroupId */
        $categoryData = $this->databaseHelper->getCachedEsiDataFromDb('universe/categories/' . $categoryId);

        if(\is_null($categoryData)) {
            $categoryData = $this->universeApi->universeCategoriesCategoryId($categoryId);

            $this->databaseHelper->writeEsiCacheDataToDb([
                'universe/categories/' . $categoryId,
                \maybe_serialize($categoryData),
                \strtotime('+1 week')
            ]);
        }

        return $categoryData;
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

    public function getDogmaAttribute(int $dogmaAttributeId) {
        $dogmaAttributeData = $this->databaseHelper->getCachedEsiDataFromDb('dogma/attributes/' . $dogmaAttributeId);

        if(\is_null($dogmaAttributeData)) {
            $dogmaAttributeData = $this->dogmaApi->dogmaAttributesAttributeId($dogmaAttributeId);

            if(!\is_null($dogmaAttributeData)) {
                $this->databaseHelper->writeEsiCacheDataToDb([
                    'dogma/attributes/' . $dogmaAttributeId,
                    \maybe_serialize($dogmaAttributeData),
                    \strtotime('+1 week')
                ]);
            }
        }

        return $dogmaAttributeData;
    }
}
