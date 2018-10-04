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

use \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\FittingHelper;
use \WordPress\Plugins\EveOnlineFittingManager\Libs\Singletons\AbstractSingleton;

\defined('ABSPATH') or die();

class Shortcodes extends AbstractSingleton {
//    /**
//     * Constructor
//     */
//    public function __construct() {
//        $this->registerShortcodes();
//    }
//
//    /**
//     * register all shortcodes
//     */
//    public function registerShortcodes() {
//        \add_shortcode('fittings', [$this, 'shortcodeFittings']);
//    }

    /**
     * Shortcode for fitting navigation inside a page or post
     *
     * allowed args:
     *      list => doctrines or shiptypes
     *
     * @param array $atts
     */
    public function shortcodeFittings($atts) {
        $args = \shortcode_atts([
            'list' => 'doctrines'
        ], $atts);

        return FittingHelper::getInstance()->getContentMenu('fitting-' . $args['list']);
    }
}
