<?php

namespace WordPress\Plugin\EveOnlineFittingManager\Libs;

use WordPress\Plugin\EveOnlineFittingManager;

\defined('ABSPATH') or die();

class PostType {
	public function __construct() {
		\add_action('init', array($this, 'customPostType'));
//		\add_action('add_meta_boxes', array($this, 'registerMetaBoxes'));
//		\add_action('save_post', array($this, 'saveMetaBoxes'));

		\add_filter('template_include', array($this, 'templateLoader'));
		\add_filter('page_template', array($this, 'registerPageTemplate'));
	} // END public function __construct()

	public function customPostType() {
		$var_sSlug = $this->getPosttypeSlug('fitting');
		$array_Labels = array(
			'name' => \__('Fitting Categories', 'eve-online-fitting-manager'),
			'singular_name' => \__('Fitting Category', 'eve-online-fitting-manager'),
			'search_items' => \__('Search Fitting Categories', 'eve-online-fitting-manager'),
			'popular_items' => \__('Popular Fitting Categories', 'eve-online-fitting-manager'),
			'all_items' => \__('All Fitting Categories', 'eve-online-fitting-manager'),
			'parent_item' => \__('Parent Fitting Categories', 'eve-online-fitting-manager'),
			'edit_item' => \__('Edit Fitting Categories', 'eve-online-fitting-manager'),
			'update_item' => \__('Update Fitting Categories', 'eve-online-fitting-manager'),
			'add_new_item' => \__('Add New Fitting Categories', 'eve-online-fitting-manager'),
			'new_item_name' => \__('New Fitting Categories', 'eve-online-fitting-manager'),
			'separate_items_with_commas' => \__('Separate Fitting Categories with commas', 'eve-online-fitting-manager'),
			'add_or_remove_items' => \__('Add or remove Fitting Categories', 'eve-online-fitting-manager'),
			'choose_from_most_used' => \__('Choose from most used Fitting Categories', 'eve-online-fitting-manager')
		);

		$array_Args = array(
			'label' => \__('Fitting Categories', 'eve-online-fitting-manager'),
			'labels' => $array_Labels,
			'public' => true,
			'hierarchical' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'args' => array(
				'orderby' => 'term_order'
			),
			'rewrite' => array(
				'slug' => $var_sSlug . '/doctrine',
				'with_front' => true
			),
			'query_var' => true
		);

		register_taxonomy('fitting-categories', array(
			'fitting'
			),
			$array_Args
		);

		register_post_type('fitting', array(
			'labels' => array(
				'name' => \__('Fittings', 'eve-online-fitting-manager'),
				'singular_name' => \__('Fitting', 'eve-online-fitting-manager')
			),
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'supports' => array(
				'title',
				'editor',
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
	} // END public function customPostType()

	/**
	 * Getting the slug for the custom post type
	 *
	 * If the pages for looping the custom post types have not the same name
	 * as the custom post type, we need to find its slug to get it working.
	 *
	 * @since Talos 1.0
	 * @author ppfeufer
	 *
	 * @param string $postType
	 */
	private function getPosttypeSlug($postType) {
		global $wpdb;

		$var_qry = '
			SELECT
				' . $wpdb->posts . '.post_name as post_name
			FROM
				' . $wpdb->posts . ',
				' . $wpdb->postmeta . '
			WHERE
				' . $wpdb->postmeta . '.meta_key = "_wp_page_template"
				AND ' . $wpdb->postmeta . '.meta_value = "page-' . $postType . '.php"
				AND ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id;';

		$slugData = $wpdb->get_var($var_qry);

		/**
		 * Returning the slug or, if not found the name of custom post type
		 */
		if(!empty($slugData)) {
			return $slugData;
		} else {
			return $postType;
		} // END if(!empty($var_sSlugData))
	} // END private function _getPosttypeSlug($var_sPosttype)

	/**
	 * Template loader.
	 *
	 * The template loader will check if WP is loading a template
	 * for a specific Post Type and will try to load the template
	 * from out 'templates' directory.
	 *
	 * @since 1.0.0
	 *
	 * @param	string	$template	Template file that is being loaded.
	 * @return	string				Template file that should be loaded.
	 */
	public function templateLoader($template) {
		$templateFile = null;

		if(\is_singular('fitting')) {
			$templateFile = 'single-fitting.php';
		} elseif(\is_archive() && \get_post_type() === 'fitting') {
			$templateFile = 'archive-fitting.php';
		} // END if(\is_singular('fitting'))

		if($templateFile !== null) {
			if(\file_exists(EveOnlineFittingManager\Helper\TemplateHelper::locateTemplate($templateFile))) {
				$template = EveOnlineFittingManager\Helper\TemplateHelper::locateTemplate($templateFile);
			} // END if(\file_exists(EveOnlineFittingManager\Helper\TemplateHelper::locateTemplate($file)))
		} // END if($templateFile !== null)

		return $template;
	} // END function templateLoader($template)

	public function registerPageTemplate($pageTemplate) {
		if(\is_page($this->getPosttypeSlug('fittings'))) {
			$pageTemplate = EveOnlineFittingManager\Helper\PluginHelper::getPluginPath('templates/page-fittings.php');
		}

		return $pageTemplate;
	}

	public static function isEditPage($newEdit = null){
		global $pagenow;

		//make sure we are on the backend
		if(!\is_admin()) {
			return false;
		}

		switch($newEdit) {
			case 'edit':
				return \in_array($pagenow, array('post.php'));
				break;

			case 'new':
				return \in_array($pagenow, array('post-new.php'));
				break;

			default:
				return \in_array($pagenow, array('post.php', 'post-new.php'));
				break;
		}
	}
} // END class PostType
