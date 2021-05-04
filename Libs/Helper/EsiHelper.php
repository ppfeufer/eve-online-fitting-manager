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

use WordPress\EsiClient\Model\Insurance\Prices;
use WordPress\EsiClient\Model\Universe\Categories\CategoryId;
use WordPress\EsiClient\Model\Universe\Groups\GroupId;
use WordPress\EsiClient\Model\Universe\Ids;
use WordPress\EsiClient\Model\Universe\Types\TypeId;
use WordPress\EsiClient\Repository\InsuranceRepository;
use WordPress\EsiClient\Repository\UniverseRepository;
use WordPress\Plugins\EveOnlineFittingManager\Libs\Singletons\AbstractSingleton;

defined('ABSPATH') or die();

class EsiHelper extends AbstractSingleton
{
    /**
     * Database Helper
     *
     * @var DatabaseHelper
     */
    private DatabaseHelper $databaseHelper;

    /**
     * ESI Universe API
     *
     * @var UniverseRepository
     */
    private UniverseRepository $universeApi;

    /**
     * ESI Insurance API
     *
     * @var InsuranceRepository
     */
    private InsuranceRepository $insuranceApi;

    /**
     * The Constructor
     */
    protected function __construct()
    {
        parent::__construct();

        $this->databaseHelper = DatabaseHelper::getInstance();

        /**
         * ESI API Client
         */
        $this->universeApi = new UniverseRepository;
        $this->insuranceApi = new InsuranceRepository;
    }

    /**
     * Getting all the needed ship information from the ESI
     *
     * @param int $itemId
     * @return array
     */
    public function getItemDataByItemId(int $itemId): array
    {
        $returnData = [
            'itemTypeInformation' => null,
            'itemGroupInformation' => null,
            'itemCategoryInformation' => null
        ];

        $itemTypeInformation = $this->getItemTypeInformation($itemId);

        if (!is_null($itemTypeInformation)) {
            $returnData['itemTypeInformation'] = $itemTypeInformation;

            $itemGroupInformation = $this->getItemGroupInformation($itemTypeInformation->getGroupId());

            if (!is_null($itemGroupInformation)) {
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
     * @return TypeId
     */
    public function getItemTypeInformation(int $itemId): TypeId
    {
        /* @var $itemTypeInformation TypeId */
        $itemTypeInformation = $this->databaseHelper->getCachedEsiDataFromDb('universe/types/' . $itemId);

        if (is_null($itemTypeInformation)) {
            $itemTypeInformation = $this->universeApi->universeTypesTypeId($itemId);

            if (is_a($itemTypeInformation, '\WordPress\EsiClient\Model\Universe\Types\TypeId')) {
                $this->databaseHelper->writeEsiCacheDataToDb([
                    'universe/types/' . $itemId,
                    maybe_serialize($itemTypeInformation),
                    strtotime('+1 week')
                ]);
            }
        }

        return $itemTypeInformation;
    }

    /**
     * Get item group data
     *
     * @param int $groupId
     * @return GroupId
     */
    public function getItemGroupInformation(int $groupId): GroupId
    {
        /* @var $groupData GroupId */
        $groupData = $this->databaseHelper->getCachedEsiDataFromDb('universe/groups/' . $groupId);

        if (is_null($groupData)) {
            $groupData = $this->universeApi->universeGroupsGroupId($groupId);

            if (is_a($groupData, '\WordPress\EsiClient\Model\Universe\Groups\GroupId')) {
                $this->databaseHelper->writeEsiCacheDataToDb([
                    'universe/groups/' . $groupId,
                    maybe_serialize($groupData),
                    strtotime('+1 week')
                ]);
            }
        }

        return $groupData;
    }

    /**
     * Get item category data
     *
     * @param int $categoryId
     * @return CategoryId
     */
    public function getItemCategoryInformation(int $categoryId): CategoryId
    {
        /* @var $categoryData CategoryId */
        $categoryData = $this->databaseHelper->getCachedEsiDataFromDb('universe/categories/' . $categoryId);

        if (is_null($categoryData)) {
            $categoryData = $this->universeApi->universeCategoriesCategoryId($categoryId);

            if (is_a($categoryData, '\WordPress\EsiClient\Model\Universe\Categories\CategoryId')) {
                $this->databaseHelper->writeEsiCacheDataToDb([
                    'universe/categories/' . $categoryId,
                    maybe_serialize($categoryData),
                    strtotime('+1 week')
                ]);
            }
        }

        return $categoryData;
    }

    /**
     * Get the IDs to an array of names
     *
     * @param array $names
     * @param string $type
     * @return array|null
     */
    public function getIdFromName(array $names, string $type): ?array
    {
        $returnData = null;

        /* @var $esiData Ids */
        $esiData = $this->universeApi->universeIds(array_values($names));

        if (is_a($esiData, '\WordPress\EsiClient\Model\Universe\Ids')) {
            switch ($type) {
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
        }

        return $returnData;
    }

    /**
     * Getting insurance prices for ship ID
     *
     * @param int $shipId
     * @return Prices|null
     */
    public function getShipInsuranceDetails(int $shipId): ?Prices
    {
        $returnValue = null;

        $insurances = $this->databaseHelper->getCachedEsiDataFromDb('insurance/prices');

        if (is_null($insurances)) {
            $insurances = $this->insuranceApi->insurancePrices('en-us');

            if (is_a($insurances['0'], '\WordPress\EsiClient\Model\Insurance\Prices')) {
                $this->databaseHelper->writeEsiCacheDataToDb([
                    'insurance/prices',
                    maybe_serialize($insurances),
                    strtotime('+1 day')
                ]);
            }
        }

        /* @var $insurance Prices */
        foreach ($insurances as $insurance) {
            if ($insurance->getTypeId() === $shipId) {
                $returnValue = $insurance;
            }
        }

        return $returnValue;
    }
}
