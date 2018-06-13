<?php

/**
 * Copyright (C) 2017 Rounon Dax
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

namespace WordPress\Plugin\EveOnlineFittingManager\Libs;

\defined('ABSPATH') or die();

/**
 * Registering the Killboard Database as its own instance of wpdb
 */
class Database extends Singletons\AbstractSingleton {
    private $pluginSettings = null;
    public $db = null;

    /**
     * Constructor
     */
    protected function __construct() {
        parent::__construct();

        $this->pluginSettings = \get_option(Helper\PluginHelper::getOptionFieldName(), Helper\PluginHelper::getPluginDefaultSettings());

        $this->db = $this->initiateKillboardDatabase();
    }

    /**
     * Initializing the Database
     *
     * @return \wpdb WordPress Database Object
     */
    private function initiateKillboardDatabase() {
        $returnValue = false;

        if(!empty($this->pluginSettings['edk-killboard-user']) && !empty($this->pluginSettings['edk-killboard-password']) && !empty($this->pluginSettings['edk-killboard-name']) && !empty($this->pluginSettings['edk-killboard-host'])) {
            $returnValue = new \wpdb($this->pluginSettings['edk-killboard-user'], $this->pluginSettings['edk-killboard-password'], $this->pluginSettings['edk-killboard-name'], $this->pluginSettings['edk-killboard-host']);
        }

        return $returnValue;
    }
}
