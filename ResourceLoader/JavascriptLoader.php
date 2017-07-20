<?php

namespace WordPress\Plugin\EveOnlineFittingManager\ResourceLoader;

class JavascriptLoader implements \WordPress\Plugin\EveOnlineFittingManager\Interfaces\AssetsInterface {
	public function init() {
		\add_action('wp_enqueue_scripts', array($this, 'enqueue'), 99);
	}

	public function enqueue() {
		if(!\is_admin()) {
			\wp_enqueue_script('bootstrap-js', \WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('bootstrap/js/bootstrap.min.js'), array('jquery'), '', true);
			\wp_enqueue_script('bootstrap-toolkit-js', \WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('bootstrap/bootstrap-toolkit/bootstrap-toolkit.min.js'), array('jquery', 'bootstrap-js'), '', true);
			\wp_enqueue_script('bootstrap-gallery-js', \WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('js/jquery.bootstrap-gallery.min.js'), array('jquery', 'bootstrap-js'), '', true);
//			\wp_enqueue_script('eve-online-fitting-manager-js', \WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('js/eve-online-fitting-manager.min.js'), array('jquery'), '', true);
		}
	}
}
