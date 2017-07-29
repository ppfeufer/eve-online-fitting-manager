<?php

/*
  Plugin Name: Page Template Plugin : 'Good To Be Bad'
  Plugin URI: http://www.wpexplorer.com/wordpress-page-templates-plugin/
  Version: 1.1.0
  Author: WPExplorer
  Author URI: http://www.wpexplorer.com/
 */

namespace WordPress\Plugin\EveOnlineFittingManager\Libs;

class TemplateLoader {
	/**
	 * A reference to an instance of this class.
	 */
	private static $instance;

	/**
	 * The array of templates that this plugin tracks.
	 */
	protected $templates;

	/**
	 * Returns an instance of this class.
	 */
	public static function getInstance() {
		if(null == self::$instance) {
			self::$instance = new TemplateLoader();
		} // END if(null == self::$instance)

		return self::$instance;
	} // END public static function getInstance()

	/**
	 * Initializes the plugin by setting filters and administration functions.
	 */
	private function __construct() {
		$this->templates = array();

		// Add a filter to the attributes metabox to inject template into the cache.
		if(\version_compare(\floatval(\get_bloginfo('version')), '4.7', '<')) {
			// 4.6 and older
			\add_filter('page_attributes_dropdown_pages_args', array($this, 'registerProjectTemplates'));
		} else {
			// Add a filter to the wp 4.7 version attributes metabox
			\add_filter('theme_page_templates', array($this, 'addNewTemplate'));
		} // END if(\version_compare(\floatval(\get_bloginfo('version')), '4.7', '<'))

		// Add a filter to the save post to inject out template into the page cache
		\add_filter('wp_insert_post_data', array($this, 'registerProjectTemplates'));


		// Add a filter to the template include to determine if the page has our
		// template assigned and return it's path
		\add_filter('template_include', array($this, 'viewProjectTemplate'));


		// Add your templates to this array.
		$this->templates = array(
			'../templates/page-fittings.php' => 'Fittings',
		);
	} // END private function __construct()

	/**
	 * Adds our template to the page dropdown for v4.7+
	 *
	 * @param array $posts_templates
	 * @return array
	 */
	public function addNewTemplate($posts_templates) {
		$posts_templates = \array_merge($posts_templates, $this->templates);

		return $posts_templates;
	} // END public function addNewTemplate($posts_templates)

	/**
	 * Adds our template to the pages cache in order to trick WordPress
	 * into thinking the template file exists where it doens't really exist.
	 *
	 * @param array $atts
	 * @return array
	 */
	public function registerProjectTemplates($atts) {
		// Create the key used for the themes cache
		$cache_key = 'page_templates-' . \md5(\get_theme_root() . '/' . \get_stylesheet());

		// Retrieve the cache list.
		// If it doesn't exist, or it's empty prepare an array
		$templates = \wp_get_theme()->get_page_templates();

		if(empty($templates)) {
			$templates = array();
		} // END if(empty($templates))

		// New cache, therefore remove the old one
		\wp_cache_delete($cache_key, 'themes');

		// Now add our template to the list of templates by merging our templates
		// with the existing templates array from the cache.
		$templates = \array_merge($templates, $this->templates);

		// Add the modified cache to allow WordPress to pick it up for listing
		// available templates
		\wp_cache_add($cache_key, $templates, 'themes', 1800);

		return $atts;
	} // END public function registerProjectTemplates($atts)

	/**
	 * Checks if the template is assigned to the page
	 *
	 * @global object $post
	 * @param array $template
	 * @return string
	 */
	public function viewProjectTemplate($template) {
		// Get global post
		global $post;

		// Return template if post is empty
		if(!$post) {
			return $template;
		} // END if(!$post)

		// Return default template if we don't have a custom one defined
		if(!isset($this->templates[\get_post_meta($post->ID, '_wp_page_template', true)])) {
			return $template;
		} // END if(!isset($this->templates[\get_post_meta($post->ID, '_wp_page_template', true)]))

		$file = \plugin_dir_path(__FILE__) . \get_post_meta($post->ID, '_wp_page_template', true);

		// Just to be safe, we check if the file exist first
		if(\file_exists($file)) {
			return $file;
		} else {
			echo $file;
		} // END if(\file_exists($file))

		// Return template
		return $template;
	} // END public function viewProjectTemplate($template)
} // END class TemplateLoader

/**
 * Starting the show ....
 */
\add_action('plugins_loaded', array('\WordPress\Plugin\EveOnlineFittingManager\Libs\TemplateLoader', 'getInstance'));
