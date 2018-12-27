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

namespace WordPress\Plugins\EveOnlineFittingManager\Libs\Helper;

use \WordPress\Plugins\EveOnlineFittingManager\Libs\Singletons\AbstractSingleton;

\defined('ABSPATH') or die();

/**
 * WP Filesystem API
 */
require_once(\ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php');
require_once(\ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php');

class CacheHelper extends AbstractSingleton {
    /**
     * Getting transient cache information / data
     *
     * @param string $transientName
     * @return mixed
     */
    public function checkTransientCache($transientName) {
        $data = \get_transient($transientName);

        return $data;
    }

    /**
     * Setting the transient cahe
     *
     * @param string $transientName cache name
     * @param mixed $data the data that is needed to be cached
     * @param type $time cache time in hours (default: 2)
     */
    public function setTransientCache($transientName, $data, $time = 2) {
        \set_transient($transientName, $data, $time * \HOUR_IN_SECONDS);
    }
}
