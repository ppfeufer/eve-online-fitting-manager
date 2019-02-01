<?php

/*
 * Copyright (C) 2017 ppfeufer
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

use \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\ {
    FittingHelper,
    TemplateHelper
};

?>
<div class="clearfix">
    <?php
    $isUpwellStructure = FittingHelper::getInstance()->isUpwellStructure($shipID);

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
        TemplateHelper::getInstance()->getTemplate('fitting-details/utilities/fitting-copy-eft-to-clipboard', [
            'eftFitting' => $eftFitting,
            'isUpwellStructure' => $isUpwellStructure,
            'columnsPerButton' => $columnsPerButton
        ]);
    }

    /**
     * Show copy permalink to clipboard button
     */
    if(isset($pluginSettings['template-detail-parts-settings']['show-copy-permalink']) && $pluginSettings['template-detail-parts-settings']['show-copy-permalink'] === 'yes') {
        TemplateHelper::getInstance()->getTemplate('fitting-details/utilities/fitting-copy-permalink-to-clipboard', [
            'isUpwellStructure' => $isUpwellStructure,
            'columnsPerButton' => $columnsPerButton
        ]);
    }
    ?>
</div>
<div class="fitting-copy-result"></div>
