<?php

namespace WordPress\Plugin\EveOnlineFittingManager\Helper;

use WordPress\Plugin\EveOnlineFittingManager;

\defined('ABSPATH') or die();

class PluginHelper {
	public static function getPluginPath($file = '') {
//		return plugin_dir_path(str_replace('helper', '', __FILE__));
		return \trailingslashit(\WP_CONTENT_DIR) . 'plugins/eve-online-fitting-manager/' . $file;
	}

	public static function getPluginUri($file = '') {
		return \trailingslashit(\WP_CONTENT_URL) . 'plugins/eve-online-fitting-manager/' . $file;
	} // END public function getThemeCacheUri()
}
