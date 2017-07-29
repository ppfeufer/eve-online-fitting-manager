<?php

namespace WordPress\Plugin\EveOnlineFittingManager\Libs;

//use WordPress\Plugin\EveOnlineFittingManager;

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
		\add_shortcode('fittings', array($this, 'shortcodeFittings'));
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
		$args = \shortcode_atts(
			array(
				'list' => 'doctrines'
			),
			$atts
		);

		return \WordPress\Plugin\EveOnlineFittingManager\Helper\FittingHelper::getContentMenu('fitting-' . $args['list']);
	} // END public function shortcodeFittings($atts)
} // END class Shortcodes
