<?php

namespace WordPress\Plugin\EveOnlineFittingManager\Libs;

//use WordPress\Plugin\EveOnlineFittingManager;

\defined('ABSPATH') or die();

class Shortcodes {
	public function __construct() {
		$this->registerShortcodes();
	}

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

		echo \WordPress\Plugin\EveOnlineFittingManager\Helper\FittingHelper::getContentMenu('fitting-' . $args['list']);
	}
}
