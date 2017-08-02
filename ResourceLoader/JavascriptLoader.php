<?php

namespace WordPress\Plugin\EveOnlineFittingManager\ResourceLoader;

/**
 * JavaScript Loader
 */
class JavascriptLoader implements \WordPress\Plugin\EveOnlineFittingManager\Interfaces\AssetsInterface {
	/**
	 * Initialize the loader
	 */
	public function init() {
		\add_action('wp_enqueue_scripts', array($this, 'enqueue'), 99);
	} // END public function init()

	/**
	 * Load the JavaScript
	 */
	public function enqueue() {
		/**
		 * Only in Frontend
		 */
		if(!\is_admin()) {
			if(\is_page(\WordPress\Plugin\EveOnlineFittingManager\Libs\PostType::getPosttypeSlug('fittings')) || \get_post_type() === 'fitting') {
				\wp_enqueue_script('bootstrap-js', \WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('bootstrap/js/bootstrap.min.js'), array('jquery'), '', true);
				\wp_enqueue_script('bootstrap-toolkit-js', \WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('bootstrap/bootstrap-toolkit/bootstrap-toolkit.min.js'), array('jquery', 'bootstrap-js'), '', true);
				\wp_enqueue_script('bootstrap-gallery-js', \WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('js/jquery.bootstrap-gallery.min.js'), array('jquery', 'bootstrap-js'), '', true);
				\wp_enqueue_script('copy-to-clipboard-js', \WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('js/copy-to-clipboard.min.js'), array('jquery'), '', true);
				\wp_enqueue_script('eve-online-fitting-manager-js', \WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('js/eve-online-fitting-manager.min.js'), array('jquery'), '', true);
				\wp_localize_script('eve-online-fitting-manager-js', 'fittingManagerL10n', $this->getJavaScriptTranslations());
			} // END if(\is_page(\WordPress\Plugin\EveOnlineFittingManager\Libs\PostType::getPosttypeSlug('fittings')) || \get_post_type() === 'fitting')
		} // END if(!\is_admin())
	} // END public function enqueue()

	/**
	 * Getting teh translation array to translate strings in JavaScript
	 *
	 * @return array
	 */
	private function getJavaScriptTranslations() {
		return array(
			'copyToClipboard' => array(
				'eft' => array(
					'text' => array(
						'success' => \__('EFT data successfully copied', 'eve-online-fitting-manager'),
						'error' => \__('Something went wrong. Nothing copied. Maybe your browser doesn\'t support this function.', 'eve-online-fitting-manager')
					)
				),
				'permalink' => array(
					'text' => array(
						'success' => \__('Permalink successfully copied', 'eve-online-fitting-manager'),
						'error' => \__('Something went wrong. Nothing copied. Maybe your browser doesn\'t support this function.', 'eve-online-fitting-manager')
					)
				)
			),
			'ajax' => array(
				'url' => \admin_url('admin-ajax.php'),
				'eveFittingMarketData' => array(
					'nonce' => \wp_create_nonce('ajax-nonce-eve-market-data-for-fitting')
				)
			)
		);
	} // END private function getJavaScriptTranslations()
} // END class JavascriptLoader implements \WordPress\Plugin\EveOnlineFittingManager\Interfaces\AssetsInterface
