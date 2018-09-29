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

namespace WordPress\Plugins\EveOnlineFittingManager\Libs\Esi\Api;

\defined('ABSPATH') or die();

class UniverseApi extends \WordPress\Plugins\EveOnlineFittingManager\Libs\Esi\Swagger {
    /**
     * Used ESI enpoints in this class
     *
     * @var array ESI enpoints
     */
    protected $esiEndpoints = [
        /**
         * Get information of an item category
         */
        'universe_categories_categoryID' => 'universe/categories/{category_id}/?datasource=tranquility',

        /**
         * Get information on an item group
         */
        'universe_groups_groupID' => 'universe/groups/{group_id}/?datasource=tranquility',

        /**
         * Resolve a set of names to IDs in the following categories:
         * agents, alliances, characters, constellations, corporations factions,
         * inventory_types, regions, stations, and systems.
         *
         * Only exact matches will be returned.
         * All names searched for are cached for 12 hours
         */
        'universe_ids' => 'universe/ids/?datasource=tranquility',

        /**
         * Resolve a set of IDs to names and categories.
         * Supported ID’s for resolving are:
         * Characters, Corporations, Alliances, Stations,
         * Solar Systems, Constellations, Regions, Types
         */
        'universe_names' => 'universe/names/?datasource=tranquility',

        /**
         * Get information on a type
         */
        'universe_types_typeID' => 'universe/types/{type_id}/?datasource=tranquility',
    ];

    /**
     * Get information on an item group
     *
     * @param int $groupID
     * @return object
     */
    public function groupsGroupId($groupID) {
        $this->setEsiMethod('get');
        $this->setEsiRoute($this->esiEndpoints['universe_groups_groupID']);
        $this->setEsiRouteParameter([
            '/{group_id}/' => $groupID
        ]);
        $this->setEsiVersion('v1');

        $groupData = $this->callEsi();

        return $groupData;
    }

    /**
     * Resolve a set of names to IDs in the following categories:
     * agents, alliances, characters, constellations, corporations factions,
     * inventory_types, regions, stations, and systems.
     *
     * Only exact matches will be returned.
     * All names searched for are cached for 12 hours
     *
     * @param array $names
     * @return object
     */
    public function universeIds(array $names) {
        $this->setEsiMethod('post');
        $this->setEsiPostParameter($names);
        $this->setEsiRoute($this->esiEndpoints['universe_ids']);
        $this->setEsiVersion('v1');

        $nameData = $this->callEsi();

        return $nameData;
    }

    /**
     * Get information on a type
     *
     * @param int $typeID
     * @return object
     */
    public function typesTypeId($typeID) {
        $this->setEsiMethod('get');
        $this->setEsiRoute($this->esiEndpoints['universe_types_typeID']);
        $this->setEsiRouteParameter([
            '/{type_id}/' => $typeID
        ]);
        $this->setEsiVersion('v3');

        $typeData = $this->callEsi();

        return $typeData;
    }
}
