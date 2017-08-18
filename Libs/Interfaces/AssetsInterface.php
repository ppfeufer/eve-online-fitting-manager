<?php
namespace WordPress\Plugin\EveOnlineFittingManager\Libs\Interfaces;

/**
 * Defines a common set of functions that any class responsible for loading
 * stylesheets, JavaScript, or other assets should implement.
 */
interface AssetsInterface {
	public function init();

	public function enqueue();
}
