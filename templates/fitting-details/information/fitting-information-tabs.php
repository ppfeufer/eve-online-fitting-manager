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

use \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\FittingHelper;

?>
<div>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#eft-fitting" aria-controls="eft-fitting" role="tab" data-toggle="tab"><h4><?php echo __('EFT Import', 'eve-online-fitting-manager'); ?></h4></a>
        </li>

        <?php
        /**
         * Fitting description
         */
        $fittingDescription = get_the_content();
        if(!empty(trim($fittingDescription))) {
            ?>
            <li role="presentation">
                <a href="#fitting-description" aria-controls="fitting-description" role="tab" data-toggle="tab"><h4><?php echo __('Information', 'eve-online-fitting-manager'); ?></h4></a>
            </li>
            <?php
        }

        /**
         * Ship description
         */
        if(isset($pluginSettings['template-detail-parts-settings']['show-ship-description']) && $pluginSettings['template-detail-parts-settings']['show-ship-description'] === 'yes') {
            ?>
            <li role="presentation">
                <a href="#ship-description" aria-controls="ship-description" role="tab" data-toggle="tab"><h4><?php echo __('Description', 'eve-online-fitting-manager'); ?></h4></a>
            </li>
            <?php
        }
        ?>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active ship-fitting-eft-import" id="eft-fitting">
            <p><?php echo nl2br($eftFitting); ?></p>
        </div>

        <?php
        /**
         * Fitting description
         */
        if(!empty(trim($fittingDescription))) {
            ?>
            <div role="tabpanel" class="tab-pane ship-fitting-description" id="fitting-description">
                <?php echo wpautop($fittingDescription); ?>
            </div>
            <?php
        }

        /**
         * Ship description
         */
        if(isset($pluginSettings['template-detail-parts-settings']['show-ship-description']) && $pluginSettings['template-detail-parts-settings']['show-ship-description'] === 'yes') {
            ?>
            <div role="tabpanel" class="tab-pane ship-description" id="ship-description">
                <?php echo FittingHelper::getInstance()->getItemDescription($shipID); ?>
            </div>
            <?php
        }
        ?>
    </div>
</div>
