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

class KillmailsRepository extends \WordPress\Plugins\EveOnlineFittingManager\Libs\Esi\Swagger {
    /**
     * Used ESI enpoints in this class
     *
     * @var array ESI enpoints
     */
    protected $esiEndpoints = [
        'killmails_killmailId_killmailHash' => 'killmails/{killmail_id}/{killmail_hash}/?datasource=tranquility'
    ];

    /**
     * Return a single killmail from its ID and hash
     *
     * @param int $killmailID The killmail ID to be queried
     * @param string $killmailHash The killmail hash for verification
     * @return \WordPress\Plugins\EveOnlineFittingManager\Libs\Esi\Model\Killmails\KillmailsKillmailId
     */
    public function killmailsKillmailIdKillmailHash($killmailID, $killmailHash) {
        $returnData = null;

        $this->setEsiMethod('get');
        $this->setEsiRoute($this->esiEndpoints['killmails_killmailId_killmailHash']);
        $this->setEsiRouteParameter([
            '/{killmail_id}/' => $killmailID,
            '/{killmail_hash}/' => $killmailHash
        ]);
        $this->setEsiVersion('v1');

        $killmailData = $this->callEsi();

        if(!\is_null($killmailData)) {
            $jsonMapper = new \WordPress\Plugins\EveOnlineFittingManager\Libs\Esi\Mapper\JsonMapper;
            $returnData = $jsonMapper->map(\json_decode($killmailData), new \WordPress\Plugins\EveOnlineFittingManager\Libs\Esi\Model\Killmails\KillmailsKillmailId);
        }

        return $returnData;
    }
}