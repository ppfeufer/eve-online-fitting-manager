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

class SearchWidget extends \WP_Widget {
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
			'classname' => 'fitting-manager-search-sidebar-widget',
			'description' => \__('Displaying the search field in your sidebar.', 'eve-online-fitting-manager')
		];

		$controlOptions = [];

		parent::__construct('fitting_manager_search_sidebar_widget', __('Fitting Manager Search Widget', 'fitting-manager-search-sidebar-widget'), $widgetOptions, $controlOptions);
	} // END public function __construct($id_base, $name, $widget_options = array(), $control_options = array())

	/**
	 * Widget Output
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget($args, $instance) {
		echo $args['before_widget'];

		echo $args['before_title'];
		echo \__('Search Doctrines', 'eve-online-fitting-manager');
		echo $args['after_title'];

		$countDoctrineShips = \get_terms([
			'taxonomy' => 'fitting-doctrines',
			'fields' => 'count'
		]);

		if($countDoctrineShips > 0) {
			?>
			<div class="fitting-sidebar-search">
				<form action="/<?php echo \WordPress\Plugin\EveOnlineFittingManager\Libs\PostType::getPosttypeSlug('fittings'); ?>/" method="GET" id="fitting_search" role="search">
					<div class="input-group">
						<label class="sr-only" for="fitting_search"><?php echo \__('Search', 'eve-online-fitting-manager') ?></label>
						<input type="text" class="form-control" id="fitting_search" name="fitting_search" placeholder="<?php echo \__('Search Ship Type', 'eve-online-fitting-manager') ?>" value="<?php echo \WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\FittingHelper::getFittingSearchQuery(true); ?>">
						<div class="input-group-btn">
							<button type="submit" class="btn btn-default">
								<span class="glyphicon glyphicon-search"></span>
							</button>
						</div>
					</div>
				</form>
			</div>
			<?php
		} // END if($countDoctrineShips > 0)

		echo $args['after_widget'];
	} // END public function widget($args, $instance)
} // END class ShiptypesWidget extends \WP_Widget
