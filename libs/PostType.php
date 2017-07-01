<?php

namespace WordPress\Plugin\EveOnlineFittingManager\Libs;

use WordPress\Plugin\EveOnlineFittingManager;

\defined('ABSPATH') or die();

class PostType {
	public function __construct() {
		\add_action('init', array($this, 'customPostType'));

		\add_filter('template_include', array($this, 'templateLoader'));
		\add_filter('page_template', array($this, 'registerPageTemplate'));
	} // END public function __construct()

	public function customPostType() {
		$var_sSlug = self::getPosttypeSlug('fittings');

		$labelsDoctrine = array(
			'name' => \__('Doctrines', 'eve-online-fitting-manager'),
			'singular_name' => \__('Doctrine', 'eve-online-fitting-manager'),
			'search_items' => \__('Search Doctrines', 'eve-online-fitting-manager'),
			'popular_items' => \__('Popular Doctrines', 'eve-online-fitting-manager'),
			'all_items' => \__('All Doctrines', 'eve-online-fitting-manager'),
			'parent_item' => \__('Parent Doctrine', 'eve-online-fitting-manager'),
			'edit_item' => \__('Edit Doctrine', 'eve-online-fitting-manager'),
			'update_item' => \__('Update Doctrine', 'eve-online-fitting-manager'),
			'add_new_item' => \__('Add New Doctrine', 'eve-online-fitting-manager'),
			'new_item_name' => \__('New Doctrine', 'eve-online-fitting-manager'),
			'separate_items_with_commas' => \__('Separate Doctrines with commas', 'eve-online-fitting-manager'),
			'add_or_remove_items' => \__('Add or remove Doctrine', 'eve-online-fitting-manager'),
			'choose_from_most_used' => \__('Choose from most used Doctrines', 'eve-online-fitting-manager')
		);

		$labelsShip = array(
			'name' => \__('Ship Types', 'eve-online-fitting-manager'),
			'singular_name' => \__('Ship Type', 'eve-online-fitting-manager'),
			'search_items' => \__('Search Ship Types', 'eve-online-fitting-manager'),
			'popular_items' => \__('Popular Ship Types', 'eve-online-fitting-manager'),
			'all_items' => \__('All Ship Types', 'eve-online-fitting-manager'),
			'parent_item' => \__('Parent Ship Type', 'eve-online-fitting-manager'),
			'edit_item' => \__('Edit Ship Type', 'eve-online-fitting-manager'),
			'update_item' => \__('Update Ship Type', 'eve-online-fitting-manager'),
			'add_new_item' => \__('Add New Ship Type', 'eve-online-fitting-manager'),
			'new_item_name' => \__('New Ship Type', 'eve-online-fitting-manager'),
			'separate_items_with_commas' => \__('Separate Ship Types with commas', 'eve-online-fitting-manager'),
			'add_or_remove_items' => \__('Add or remove Ship Type', 'eve-online-fitting-manager'),
			'choose_from_most_used' => \__('Choose from most used Ship Types', 'eve-online-fitting-manager')
		);

		$argsTaxDoctrine = array(
			'label' => \__('Doctrines', 'eve-online-fitting-manager'),
			'labels' => $labelsDoctrine,
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

		$argsTaxShip = array(
			'label' => \__('Ship Types', 'eve-online-fitting-manager'),
			'labels' => $labelsShip,
			'public' => true,
			'hierarchical' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'args' => array(
				'orderby' => 'term_order'
			),
			'rewrite' => array(
				'slug' => $var_sSlug . '/ship',
				'with_front' => true
			),
			'query_var' => true
		);

		register_taxonomy('fitting-doctrines', array('fitting'), $argsTaxDoctrine);
		register_taxonomy('fitting-ships', array('fitting'), $argsTaxShip);

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
				'revisions',
				'custom-fields'
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
	public static function getPosttypeSlug($postType) {
		global $wpdb;

		$var_qry = '
			SELECT
				' . $wpdb->posts . '.post_name as post_name
			FROM
				' . $wpdb->posts . ',
				' . $wpdb->postmeta . '
			WHERE
				' . $wpdb->postmeta . '.meta_key = "_wp_page_template"
				AND ' . $wpdb->postmeta . '.meta_value = "../templates/page-' . $postType . '.php"
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
//		} elseif(\is_archive() && \get_post_type() === 'fitting' && \is_tax('fitting-doctrines')) {
		} elseif(\is_archive() && \is_tax('fitting-doctrines')) {
			$templateFile = 'archive-fitting.php';
//		} elseif(\is_archive() && \get_post_type() === 'fitting' && \is_tax('fitting-ships')) {
		} elseif(\is_archive() && \is_tax('fitting-ships')) {
			$templateFile = 'archive-ship.php';
		} // END if(\is_singular('fitting'))

		if($templateFile !== null) {
			if(\file_exists(EveOnlineFittingManager\Helper\TemplateHelper::locateTemplate($templateFile))) {
				$template = EveOnlineFittingManager\Helper\TemplateHelper::locateTemplate($templateFile);
			} // END if(\file_exists(EveOnlineFittingManager\Helper\TemplateHelper::locateTemplate($file)))
		} // END if($templateFile !== null)

		return $template;
	} // END function templateLoader($template)

	/**
	 * Registering a page template
	 *
	 * @param string $pageTemplate
	 * @return string
	 */
	public function registerPageTemplate($pageTemplate) {
		if(\is_page(self::getPosttypeSlug('fittings'))) {
			$pageTemplate = EveOnlineFittingManager\Helper\PluginHelper::getPluginPath('templates/page-fittings.php');
		} // END if(\is_page($this->getPosttypeSlug('fittings')))

		return $pageTemplate;
	} // END public function registerPageTemplate($pageTemplate)

	/**
	 * Determine the type of teh edit page in backend
	 *
	 * @global array $pagenow
	 * @param string $newEdit
	 * @return boolean
	 */
	public static function isEditPage($newEdit = null) {
		global $pagenow;

		//make sure we are on the backend
		if(!\is_admin()) {
			return false;
		} // END if(!\is_admin())

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
		} // END switch($newEdit)
	} // END public static function isEditPage($newEdit = null)
} // END class PostType
