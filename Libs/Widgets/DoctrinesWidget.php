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

namespace WordPress\Plugin\EveOnlineFittingManager\Libs\Widgets;

\defined('ABSPATH') or die();

class DoctrinesWidget extends \WP_Widget {
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
			'classname' => 'fitting-manager-doctrine-sidebar-widget',
			'description' => \__('Displaying the doctrine list in your sidebar.', 'eve-online-fitting-manager')
		];

		$controlOptions = [];

		parent::__construct('fitting_manager_doctrine_sidebar_widget', __('Fitting Manager Doctrine Widget', 'fitting-manager-doctrine-sidebar-widget'), $widgetOptions, $controlOptions);
	} // END public function __construct($id_base, $name, $widget_options = array(), $control_options = array())

	/**
	 * Widget Output
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget($args, $instance) {
		echo $args['before_widget'];

		/**
		 * Filter the Navigation by doctrines
		 */
		$countDoctrineShips = \get_terms([
			'taxonomy' => 'fitting-doctrines',
			'fields' => 'count'
		]);

		if($countDoctrineShips > 0) {
			echo $args['before_title'];
			echo \__('Doctrines', 'eve-online-fitting-manager');
			echo $args['after_title'];
			echo \WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\FittingHelper::getSidebarMenu('fitting-doctrines');
		} // END if($countDoctrineShips > 0)

		echo $args['after_widget'];
	} // END public function widget($args, $instance)
} // END class DoctrinesWidget extends \WP_Widget
