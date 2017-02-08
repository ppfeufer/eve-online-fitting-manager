<?php

namespace WordPress\Plugin\EveOnlineFittingManager\Libs;

use WordPress\Plugin\EveOnlineFittingManager;

\defined('ABSPATH') or die();

class PostType {

	public function __construct() {
		\add_action('init', array($this, 'customPostType'));
		\add_filter('template_include', array($this,'templateSwitch'));
	}

	public function customPostType() {
		$var_sSlug = $this->_getPosttypeSlug('fitting');
		$array_Labels = array(
			'name' => __('Fitting Categories', 'eve-online-fitting-manager'),
			'singular_name' => __('Fitting Category', 'eve-online-fitting-manager'),
			'search_items' => __('Search Fitting Categories', 'eve-online-fitting-manager'),
			'popular_items' => __('Popular Fitting Categories', 'eve-online-fitting-manager'),
			'all_items' => __('All Fitting Categories', 'eve-online-fitting-manager'),
			'parent_item' => __('Parent Fitting Categories', 'eve-online-fitting-manager'),
			'edit_item' => __('Edit Fitting Categories', 'eve-online-fitting-manager'),
			'update_item' => __('Update Fitting Categories', 'eve-online-fitting-manager'),
			'add_new_item' => __('Add New Fitting Categories', 'eve-online-fitting-manager'),
			'new_item_name' => __('New Fitting Categories', 'eve-online-fitting-manager'),
			'separate_items_with_commas' => __('Separate Fitting Categories with commas', 'eve-online-fitting-manager'),
			'add_or_remove_items' => __('Add or remove Fitting Categories', 'eve-online-fitting-manager'),
			'choose_from_most_used' => __('Choose from most used Fitting Categories', 'eve-online-fitting-manager')
		);

		$array_Args = array(
			'label' => __('Fitting Categories', 'eve-online-fitting-manager'),
			'labels' => $array_Labels,
			'public' => true,
			'hierarchical' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'args' => array(
				'orderby' => 'term_order'
			),
			'rewrite' => array(
				'slug' => $var_sSlug . '/category',
				'with_front' => true
			),
			'query_var' => true
		);

		register_taxonomy('fitting-categories', array(
			'fitting'
		), $array_Args);

		register_post_type('fitting', array(
			'labels' => array(
				'name' => __('Fittings', 'eve-online-fitting-manager'),
				'singular_name' => __('Fitting', 'eve-online-fitting-manager')
			),
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'supports' => array(
				'title',
//				'editor',
				'author',
				'thumbnail',
//				'excerpt',
				'revisions',
//				'trackbacks',
//				'comments'
			),
			'rewrite' => array(
				'slug' => $var_sSlug,
				'with_front' => true
			)
		));
	}

	/**
	 * Getting the slug for the custom post type
	 *
	 * If the pages for looping the custom post types have not the same name
	 * as the custom post type, we need to find its slug to get it working.
	 *
	 * @since Talos 1.0
	 * @author ppfeufer
	 *
	 * @param string $var_sPosttype
	 */
	private function _getPosttypeSlug($var_sPosttype) {
		global $wpdb;

		$var_qry = '
			SELECT
				' . $wpdb->posts . '.post_name as post_name
			FROM
				' . $wpdb->posts . ',
				' . $wpdb->postmeta . '
			WHERE
				' . $wpdb->postmeta . '.meta_key = "_wp_page_template"
				AND ' . $wpdb->postmeta . '.meta_value = "page-' . $var_sPosttype . '.php"
				AND ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id;';

		$var_sSlugData = $wpdb->get_var($var_qry);

		/**
		 * Returning the slug or, if not found the name of custom post type
		 */
		if(!empty($var_sSlugData)) {
			return $var_sSlugData;
		} else {
			return $var_sPosttype;
		} // END if(!empty($var_sSlugData))
	} // END private function _get_posttype_slug($var_sPosttype)

	public function templateSwitch($template) {
		// Post ID
		$post_id = \get_the_ID();

		// For all other CPT
		if(\get_post_type($post_id) !== 'fitting') {
			return $template;
		}

		// Else use custom template
		if(\is_single()) {
			return $this->templateHierarchy('single-' . \get_post_type());
		}
	}

	function templateHierarchy($template) {
		// Get the template slug
		$template_slug = \rtrim($template, '.php');
		$template = $template_slug . '.php';

		// Check if a custom template exists in the theme folder, if not, load the plugin template file
		if($theme_file = \locate_template(array('templates/' . $template))) {
			$file = $theme_file;
		} else {
			$file = '/templates/' . $template;
		}

		return \apply_filters('eve-online-fitting-manager-template_' . $template, $file);
	}
}