<?php

namespace WordPress\Plugin\EveOnlineFittingManager\ResourceLoader;

/**
 * CSS Loader
 */
class CssLoader implements \WordPress\Plugin\EveOnlineFittingManager\Interfaces\AssetsInterface {
	/**
	 * Initialize the loader
	 */
	public function init() {
		\add_action('wp_enqueue_scripts', array($this, 'enqueue'), 99);
	}

	/**
	 * Load the styles
	 */
	public function enqueue() {
		if(!\is_admin()) {
			\wp_enqueue_style('bootstrap', \WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('bootstrap/css/bootstrap.min.css'));
			\wp_enqueue_style('eve-online-fitting-manager', \WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('css/eve-online-fitting-manager.min.css'));
		}
	}
}
