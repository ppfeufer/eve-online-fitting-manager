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

namespace WordPress\Plugins\EveOnlineFittingManager\Libs\Esi\Repository;

\defined('ABSPATH') or die();

class CharacterRepository extends \WordPress\Plugins\EveOnlineFittingManager\Libs\Esi\Swagger {
    /**
     * Used ESI enpoints in this class
     *
     * @var array ESI enpoints
     */
    protected $esiEndpoints = [
        'characters_characterId' => 'characters/{character_id}/?datasource=tranquility',
        'characters_characterId_corporationhistory' => 'characters/{character_id}/corporationhistory/?datasource=tranquility',
        'characters_characterId_portrait' => 'characters/{character_id}/portrait/?datasource=tranquility',
        'characters_affiliation' => 'characters/affiliation/?datasource=tranquility'
    ];

    /**
     * Public information about a character
     *
     * @param int $characterID An EVE character ID
     * @return \WordPress\Plugins\EveOnlineFittingManager\Libs\Esi\Model\Character\CharactersCharacterId
     */
    public function charactersCharacterId($characterID) {
        $returnValue = null;

        $this->setEsiMethod('get');
        $this->setEsiRoute($this->esiEndpoints['characters_characterId']);
        $this->setEsiRouteParameter([
            '/{character_id}/' => $characterID
        ]);
        $this->setEsiVersion('v4');

        $characterData = $this->callEsi();

        if(!\is_null($characterData)) {
            $jsonMapper = new \WordPress\Plugins\EveOnlineFittingManager\Libs\Esi\Mapper\JsonMapper;
            $returnValue = $jsonMapper->map(\json_decode($characterData), new \WordPress\Plugins\EveOnlineFittingManager\Libs\Esi\Model\Character\CharactersCharacterId);
        }

        return $returnValue;
    }

    /**
     * Bulk lookup of character IDs to corporation, alliance and faction
     *
     * @param array $characterIds The character IDs to fetch affiliations for. All characters must exist, or none will be returned
     * @return array
     */
    public function charactersAffiliation($characterIds = []) {
        $returnValue = null;

        $this->setEsiMethod('post');
        $this->setEsiPostParameter($characterIds);
        $this->setEsiRoute($this->esiEndpoints['characters_affiliation']);
        $this->setEsiVersion('v1');

        $affiliationData = $this->callEsi();

        if(!\is_null($affiliationData)) {
            $jsonMapper = new \WordPress\Plugins\EveOnlineFittingManager\Libs\Esi\Mapper\JsonMapper;
            $returnValue = $jsonMapper->mapArray(\json_decode($affiliationData), [], '\\WordPress\Plugins\EveOnlineFittingManager\Libs\Esi\Model\Character\CharactersAffiliation');
        }

        return $returnValue;
    }
}
