<div class="clearfix">
	<?php
	/**
	 * Show the Osmium fitting link
	 */
	if(isset($pluginSettings['template-detail-parts-settings']['show-osmium-link']) && $pluginSettings['template-detail-parts-settings']['show-osmium-link'] === 'yes') {
		\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting-details/utilities/fitting-osmium', array(
			'shipID' => $shipID,
			'fittingDna' => $fittingDna
		));
	} // END if(isset($pluginSettings['template-detail-parts-settings']['show-osmium-link']) && $pluginSettings['template-detail-parts-settings']['show-osmium-link'] === 'yes')

	/**
	 * Show copy eft data to clipboard button
	 */
	if(isset($pluginSettings['template-detail-parts-settings']['show-copy-eft']) && $pluginSettings['template-detail-parts-settings']['show-copy-eft'] === 'yes') {
		\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting-details/utilities/fitting-copy-eft-to-clipboard', array(
			'eftFitting' => $eftFitting
		));
	} // END if(isset($pluginSettings['template-detail-parts-settings']['show-copy-eft']) && $pluginSettings['template-detail-parts-settings']['show-copy-eft'] === 'yes')

	/**
	 * Show copy permalink to clipboard button
	 */
	if(isset($pluginSettings['template-detail-parts-settings']['show-copy-permalink']) && $pluginSettings['template-detail-parts-settings']['show-copy-permalink'] === 'yes') {
		\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting-details/utilities/fitting-copy-permalink-to-clipboard');
	} // END if(isset($pluginSettings['template-detail-parts-settings']['show-copy-permalink']) && $pluginSettings['template-detail-parts-settings']['show-copy-permalink'] === 'yes')
	?>
</div>
<div class="fitting-copy-result"></div>
