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
	}

	/**
	 * Load the JavaScript
	 */
	public function enqueue() {
		if(!\is_admin()) {
			\wp_enqueue_script('bootstrap-js', \WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('bootstrap/js/bootstrap.min.js'), array('jquery'), '', true);
			\wp_enqueue_script('bootstrap-toolkit-js', \WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('bootstrap/bootstrap-toolkit/bootstrap-toolkit.min.js'), array('jquery', 'bootstrap-js'), '', true);
			\wp_enqueue_script('bootstrap-gallery-js', \WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('js/jquery.bootstrap-gallery.min.js'), array('jquery', 'bootstrap-js'), '', true);
			\wp_enqueue_script('copy-to-clipboard-js', \WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('js/copy-to-clipboard.min.js'), array('jquery'), '', true);
			\wp_enqueue_script('eve-online-fitting-manager-js', \WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('js/eve-online-fitting-manager.min.js'), array('jquery'), '', true);
			\wp_localize_script('eve-online-fitting-manager-js', 'fittingManagerL10n', $this->getCopyToClipboardJstranslations());
		}
	}

	private function getCopyToClipboardJstranslations() {
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
	}
}
