<?php

/**
 * Copyright (C) 2017 ppfeufer
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
namespace WordPress\Plugins\EveOnlineFittingManager\Libs\Esi\Api;

\defined('ABSPATH') or die();

class DogmaApi extends \WordPress\Plugins\EveOnlineFittingManager\Libs\Esi\Swagger {
    /**
     * Used ESI enpoints in this class
     *
     * @var array ESI enpoints
     */
    protected $esiEndpoints = [
        /**
         * Get a list of dogma attribute ids
         */
        'dogma_attributes' => 'dogma/attributes/?datasource=tranquility',

        /**
         * Get information on a dogma attribute
         */
        'dogma_attributes_attributeID' => 'dogma/attributes/{attribute_id}/?datasource=tranquility',

        /**
         * Returns info about a dynamic item resulting from mutation with a mutaplasmid.
         */
        'dogma_dynamic_items_typeID_itemID' => 'dogma/dynamic/items/{type_id}/{item_id}/?datasource=tranquility',

        /**
         * Get a list of dogma effect ids
         */
        'dogma_effects' => 'dogma/effects/?datasource=tranquility',

        /**
         * Get a list of dogma effect ids
         */
        'dogma_effects_effectID' => 'dogma/effects/{effect_id}/?datasource=tranquility'
    ];

    /**
     * Get a list of dogma attribute ids
     *
     * @param int $typeID
     * @return object
     */
    public function dogmaAttributes($typeID) {
        $this->setEsiRoute($this->esiEndpoints['dogma_attributes']);
        $this->setEsiVersion('v1');

        $typeData = $this->callEsi();

        return $typeData;
    }
}
