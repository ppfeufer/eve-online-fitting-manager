<div class="clearfix">
    <?php
    $isUpwellStructure = \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\FittingHelper::isUpwellStructure($shipID);

    /**
     * Get cols count
     */
    $colsCount = null;

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
     * Show copy eft data to clipboard button
     */
    if(isset($pluginSettings['template-detail-parts-settings']['show-copy-eft']) && $pluginSettings['template-detail-parts-settings']['show-copy-eft'] === 'yes') {
        \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\TemplateHelper::getTemplate('fitting-details/utilities/fitting-copy-eft-to-clipboard', [
            'eftFitting' => $eftFitting,
            'isUpwellStructure' => $isUpwellStructure,
            'columnsPerButton' => $columnsPerButton
        ]);
    }

    /**
     * Show copy permalink to clipboard button
     */
    if(isset($pluginSettings['template-detail-parts-settings']['show-copy-permalink']) && $pluginSettings['template-detail-parts-settings']['show-copy-permalink'] === 'yes') {
        \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\TemplateHelper::getTemplate('fitting-details/utilities/fitting-copy-permalink-to-clipboard', [
            'isUpwellStructure' => $isUpwellStructure,
            'columnsPerButton' => $columnsPerButton
        ]);
    }
    ?>
</div>
<div class="fitting-copy-result"></div>
