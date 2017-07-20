<?php

namespace WordPress\Plugin\EveOnlineFittingManager\ResourceLoader;

class CssLoader implements \WordPress\Plugin\EveOnlineFittingManager\Interfaces\AssetsInterface {
	public function init() {
		\add_action('wp_enqueue_scripts', array($this, 'enqueue'), 99);
	}

	public function enqueue() {
		if(!\is_admin()) {
			\wp_enqueue_style('bootstrap', \WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('bootstrap/css/bootstrap.min.css'));
			\wp_enqueue_style('eve-online-fitting-manager', \WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('css/eve-online-fitting-manager.min.css'));
		}
	}
}
