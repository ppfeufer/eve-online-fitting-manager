<?php

namespace WordPress\Plugin\EveOnlineFittingManager\Libs;

\defined('ABSPATH') or die();

class Shortcodes {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->registerShortcodes();
	} // END public function __construct()

	/**
	 * register all shortcodes
	 */
	public function registerShortcodes() {
		\add_shortcode('fittings', [$this, 'shortcodeFittings']);
	} // END public function registerShortcodes()

	/**
	 * Shortcode for fitting navigation inside a page or post
	 *
	 * allowed args:
	 *	list => doctrines or shiptypes
	 *
	 * @param array $atts
	 */
	public function shortcodeFittings($atts) {
		$args = \shortcode_atts([
			'list' => 'doctrines'
		], $atts);

		return Helper\FittingHelper::getContentMenu('fitting-' . $args['list']);
	} // END public function shortcodeFittings($atts)
} // END class Shortcodes
