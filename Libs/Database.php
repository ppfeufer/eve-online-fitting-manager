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

namespace WordPress\Plugins\EveOnlineFittingManager\Libs;

use \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\PluginHelper;
use \WordPress\Plugins\EveOnlineFittingManager\Libs\Singletons\AbstractSingleton;

\defined('ABSPATH') or die();

/**
 * Registering the Killboard Database as its own instance of wpdb
 */
class Database extends AbstractSingleton {
    /**
     * Plugin settings
     *
     * @var array
     */
    protected $pluginSettings = null;

    /**
     * Database connector
     *
     * @var \wpdb
     */
    public $db = null;

    /**
     * Constructor
     */
    protected function __construct() {
        parent::__construct();

        $this->pluginSettings = \get_option(PluginHelper::getInstance()->getOptionFieldName(), PluginHelper::getInstance()->getPluginDefaultSettings());

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
