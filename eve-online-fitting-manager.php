<?php
/**
 * Plugin Name: EVE Online Fitting Manager for WordPress
 * Plugin URI: https://github.com/ppfeufer/eve-online-fitting-manager
 * Git URI: https://github.com/ppfeufer/eve-online-fitting-manager
 * Description: A little management tool for your doctrine fittings for your WordPress website. (Best with a theme running with <a href="http://getbootstrap.com/">Bootstrap</a>)
 * Version: 1.0
 * Author: Rounon Dax
 * Author URI: http://yulaifederation.net
 * Text Domain: eve-online-fitting-manager
 * Domain Path: /l10n
 */

namespace WordPress\Plugin\EveOnlineFittingManager;

class EveOnlineFittingManager {
	private $textDomain = 'eve-online-fitting-manager';

	public function __construct() {
		$this->addTextDomain();

		/**
		 * Github Updater
		 */
		if(\is_admin()) {
			/**
			 * Github Update Parser
			 *
			 * @since 1.0
			 */
			require_once(\plugin_dir_path(__FILE__) . 'libs/GithubUpdater.php');
			new Libs\GithubPluginUpdater(__FILE__, 'ppfeufer', 'eve-online-fitting-manager');
		} // END if(\is_admin())
	} // END public function __construct()

	/**
	 * Setting up our text domain for translations
	 */
	public function addTextDomain() {
		if(\function_exists('\load_plugin_textdomain')) {
			\load_plugin_textdomain($this->textDomain, \PLUGINDIR . '/' . \dirname(\plugin_basename(__FILE__)) . '/l10n', \dirname(\plugin_basename(__FILE__)) . '/l10n');
		} // END if(\function_exists('\load_plugin_textdomain'))
	} // END public function addTextDomain()
} // END class EveOnlineFittingManager
