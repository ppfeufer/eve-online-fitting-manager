<?php

namespace WordPress\Plugin\EveOnlineFittingManager\Helper;

use WordPress\Plugin\EveOnlineFittingManager;

\defined('ABSPATH') or die();

class PluginHelper {
	public static function getPluginPath() {
		return plugin_dir_path(str_replace('helper', '', __FILE__));
	}
}
