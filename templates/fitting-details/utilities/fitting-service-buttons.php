<div class="clearfix">
	<?php
	$isUpwellStructure = \WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\FittingHelper::isUpwellStructure($shipID);

	/**
	 * Get cols count
	 */
	$colsCount = null;

	// Check for o.smium button
	if(isset($pluginSettings['template-detail-parts-settings']['show-osmium-link']) && $pluginSettings['template-detail-parts-settings']['show-osmium-link'] === 'yes') {
		if($isUpwellStructure === false) {
			$colsCount++;
		}
	}

	// Check for copy EFT button
	if(isset($pluginSettings['template-detail-parts-settings']['show-copy-eft']) && $pluginSettings['template-detail-parts-settings']['show-copy-eft'] === 'yes') {
		$colsCount++;
	}

	// Check for copy link button
	if(isset($pluginSettings['template-detail-parts-settings']['show-copy-permalink']) && $pluginSettings['template-detail-parts-settings']['show-copy-permalink'] === 'yes') {
		$colsCount++;
	}

	$columnsPerButton = 12;

	if(!is_null($columnsPerButton)) {
		$columnsPerButton = $columnsPerButton / $colsCount;
	}

	/**
	 * Show the Osmium fitting link
	 */
	if(isset($pluginSettings['template-detail-parts-settings']['show-osmium-link']) && $pluginSettings['template-detail-parts-settings']['show-osmium-link'] === 'yes') {
		if($isUpwellStructure === false) {
			\WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\TemplateHelper::getTemplate('fitting-details/utilities/fitting-osmium', [
				'fittingDna' => $fittingDna,
				'columnsPerButton' => $columnsPerButton
			]);
		} // END if(\WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\FittingHelper::isUpwellStructure($shipID) === false)
	} // END if(isset($pluginSettings['template-detail-parts-settings']['show-osmium-link']) && $pluginSettings['template-detail-parts-settings']['show-osmium-link'] === 'yes')

	/**
	 * Show copy eft data to clipboard button
	 */
	if(isset($pluginSettings['template-detail-parts-settings']['show-copy-eft']) && $pluginSettings['template-detail-parts-settings']['show-copy-eft'] === 'yes') {
		\WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\TemplateHelper::getTemplate('fitting-details/utilities/fitting-copy-eft-to-clipboard', [
			'eftFitting' => $eftFitting,
			'isUpwellStructure' => $isUpwellStructure,
			'columnsPerButton' => $columnsPerButton
		]);
	} // END if(isset($pluginSettings['template-detail-parts-settings']['show-copy-eft']) && $pluginSettings['template-detail-parts-settings']['show-copy-eft'] === 'yes')

	/**
	 * Show copy permalink to clipboard button
	 */
	if(isset($pluginSettings['template-detail-parts-settings']['show-copy-permalink']) && $pluginSettings['template-detail-parts-settings']['show-copy-permalink'] === 'yes') {
		\WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\TemplateHelper::getTemplate('fitting-details/utilities/fitting-copy-permalink-to-clipboard', [
			'isUpwellStructure' => $isUpwellStructure,
			'columnsPerButton' => $columnsPerButton
		]);
	} // END if(isset($pluginSettings['template-detail-parts-settings']['show-copy-permalink']) && $pluginSettings['template-detail-parts-settings']['show-copy-permalink'] === 'yes')
	?>
</div>
<div class="fitting-copy-result"></div>
