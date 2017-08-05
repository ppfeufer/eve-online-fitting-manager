<div class="clearfix">
	<?php
	$isUpwellStructure = \WordPress\Plugin\EveOnlineFittingManager\Helper\FittingHelper::isUpwellStructure($shipID);

	/**
	 * Show the Osmium fitting link
	 */
	if(isset($pluginSettings['template-detail-parts-settings']['show-osmium-link']) && $pluginSettings['template-detail-parts-settings']['show-osmium-link'] === 'yes') {
		if($isUpwellStructure === false) {
			\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting-details/utilities/fitting-osmium', array(
				'fittingDna' => $fittingDna
			));
		} // END if(\WordPress\Plugin\EveOnlineFittingManager\Helper\FittingHelper::isUpwellStructure($shipID) === false)
	} // END if(isset($pluginSettings['template-detail-parts-settings']['show-osmium-link']) && $pluginSettings['template-detail-parts-settings']['show-osmium-link'] === 'yes')

	/**
	 * Show copy eft data to clipboard button
	 */
	if(isset($pluginSettings['template-detail-parts-settings']['show-copy-eft']) && $pluginSettings['template-detail-parts-settings']['show-copy-eft'] === 'yes') {
		\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting-details/utilities/fitting-copy-eft-to-clipboard', array(
			'eftFitting' => $eftFitting,
			'isUpwellStructure' => $isUpwellStructure
		));
	} // END if(isset($pluginSettings['template-detail-parts-settings']['show-copy-eft']) && $pluginSettings['template-detail-parts-settings']['show-copy-eft'] === 'yes')

	/**
	 * Show copy permalink to clipboard button
	 */
	if(isset($pluginSettings['template-detail-parts-settings']['show-copy-permalink']) && $pluginSettings['template-detail-parts-settings']['show-copy-permalink'] === 'yes') {
		\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting-details/utilities/fitting-copy-permalink-to-clipboard', array(
			'isUpwellStructure' => $isUpwellStructure
		));
	} // END if(isset($pluginSettings['template-detail-parts-settings']['show-copy-permalink']) && $pluginSettings['template-detail-parts-settings']['show-copy-permalink'] === 'yes')
	?>
</div>
<div class="fitting-copy-result"></div>
