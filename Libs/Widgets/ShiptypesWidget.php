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

namespace WordPress\Plugins\EveOnlineFittingManager\Libs\Widgets;

use \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\FittingHelper;
use \WP_Widget;

\defined('ABSPATH') or die();

class ShiptypesWidget extends WP_Widget {
    /**
     * Root ID for all widgets of this type.
     *
     * @since 2.8.0
     * @access public
     * @var mixed|string
     */
    public $id_base;

    /**
     * Name for this widget type.
     *
     * @since 2.8.0
     * @access public
     * @var string
     */
    public $name;

    /**
     * Unique ID number of the current instance.
     *
     * @since 2.8.0
     * @var bool|int
     */
    public $number = false;

    /**
     * Constructor
     */
    public function __construct() {
        $widgetOptions = [
            'classname' => 'fitting-manager-shiptypes-sidebar-widget',
            'description' => \__('Displaying the ship types list in your sidebar.', 'eve-online-fitting-manager')
        ];

        $controlOptions = [];

        parent::__construct('fitting_manager_shiptypes_sidebar_widget', \__('Fitting Manager Ship Types Widget', 'fitting-manager-shiptypes-sidebar-widget'), $widgetOptions, $controlOptions);
    }

    /**
     * Widget Output
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];

        /**
         * Filter the Navigation by ship types
         */
        $countShipTypes = \get_terms([
            'taxonomy' => 'fitting-ships',
            'fields' => 'count'
        ]);

        if($countShipTypes > 0) {
            echo $args['before_title'];
            echo \__('Ship Types', 'eve-online-fitting-manager');
            echo $args['after_title'];
            echo FittingHelper::getInstance()->getSidebarMenu('fitting-ships');
        }

        echo $args['after_widget'];
    }
}
