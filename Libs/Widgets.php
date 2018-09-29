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

\defined('ABSPATH') or die();

class Widgets {
    /**
     * Constructor
     */
    public function __construct() {
        ;
    }

    /**
     * Initialize the show
     */
    public function init() {
        \add_action('init', [$this, 'registerSidebar'], 99);
        \add_action('widgets_init', \create_function('', 'return register_widget("WordPress\Plugins\EveOnlineFittingManager\Libs\Widgets\DoctrinesWidget");'));
        \add_action('widgets_init', \create_function('', 'return register_widget("WordPress\Plugins\EveOnlineFittingManager\Libs\Widgets\ShiptypesWidget");'));
        \add_action('widgets_init', \create_function('', 'return register_widget("WordPress\Plugins\EveOnlineFittingManager\Libs\Widgets\SearchWidget");'));
    }

    /**
     * Register our sidebar
     */
    public function registerSidebar() {
        \register_sidebar([
            'name' => \__('Fitting Manager Sidebar', 'eve-online-fitting-manager'),
            'description' => \__('Sidebar to use with your Fitting Manager pages.', 'eve-online-fitting-manager'),
            'id' => 'sidebar-fitting-manager',
            'before_widget' => '<aside><div id="%1$s" class="widget %2$s">',
            'after_widget' => "</div></aside>",
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
        ]);
    }
}
