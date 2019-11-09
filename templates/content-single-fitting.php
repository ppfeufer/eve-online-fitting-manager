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

use \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\EftHelper;
use \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\FittingHelper;
use \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\PluginHelper;
use \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\TemplateHelper;

defined('ABSPATH') or die();

// Plugin Settings
$pluginSettings = PluginHelper::getInstance()->getPluginSettings();

// Information to build the EFT data structure
$shipID = get_post_meta(get_the_ID(), 'eve-online-fitting-manager_fitting_ship_ID', true);
$fittingType = get_post_meta(get_the_ID(), 'eve-online-fitting-manager_fitting_name', true);
$highSlots = get_post_meta(get_the_ID(), 'eve-online-fitting-manager_fitting_high_slots', true);
$midSlots = get_post_meta(get_the_ID(), 'eve-online-fitting-manager_fitting_mid_slots', true);
$lowSlots = get_post_meta(get_the_ID(), 'eve-online-fitting-manager_fitting_low_slots', true);
$rigSlots = get_post_meta(get_the_ID(), 'eve-online-fitting-manager_fitting_rig_slots', true);
$subSystems = get_post_meta(get_the_ID(), 'eve-online-fitting-manager_fitting_subsystems', true);
$serviceSlots = get_post_meta(get_the_ID(), 'eve-online-fitting-manager_fitting_upwellservices', true);
$drones = get_post_meta(get_the_ID(), 'eve-online-fitting-manager_fitting_drones', true);
$charges = get_post_meta(get_the_ID(), 'eve-online-fitting-manager_fitting_charges', true);
$fuel = get_post_meta(get_the_ID(), 'eve-online-fitting-manager_fitting_fuel', true);
$implantsAndBooster = get_post_meta(get_the_ID(), 'eve-online-fitting-manager_fitting_implants_and_booster', true);
$fittingDna = get_post_meta(get_the_ID(), 'eve-online-fitting-manager_fitting_dna', true);

// Build EFT data
$eftFitting = EftHelper::getInstance()->getEftImportFromFitting([
    'shipID' => $shipID,
    'fittingType' => $fittingType,
    'highSlots' => $highSlots,
    'midSlots' => $midSlots,
    'lowSlots' => $lowSlots,
    'rigSlots' => $rigSlots,
    'subSystems' => $subSystems,
    'serviceSlots' => $serviceSlots,
    'drones' => $drones,
    'charges' => $charges,
    'fuel' => $fuel,
    'implantsAndBooster' => $implantsAndBooster
]);
?>

<header class="entry-header">
    <h1 class="entry-title">
        <img src="<?php echo FittingHelper::getInstance()->getShipImageById(get_post_meta(get_the_ID(), 'eve-online-fitting-manager_fitting_ship_ID', true), 64); ?>">
        <?php the_title(); ?>
    </h1>

    <aside class="entry-details">
        <p class="meta">
            <?php edit_post_link(__('Edit', 'eve-online-fitting-manager')); ?>
        </p>
    </aside><!--end .entry-details -->
</header><!--end .entry-header -->

<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix content-single template-content-single-fitting'); ?>>
    <section class="post-content">
        <div class="entry-content">
            <?php
            /**
             * Show doctrines tha fitting is used in
             */
            TemplateHelper::getInstance()->getTemplate('fitting-details/information/fitting-marker');
            ?>

            <div class="row">
                <div class="col-lg-7 col-xl-6 ship-fitting-visualization">
                    <?php
                    /**
                     * Show visual fitting
                     */
                    if(isset($pluginSettings['template-detail-parts-settings']['show-visual-fitting']) && $pluginSettings['template-detail-parts-settings']['show-visual-fitting'] === 'yes') {
                        TemplateHelper::getInstance()->getTemplate('fitting-details/visualization/fitting-ring', [
                            'shipID' => $shipID,
                            'highSlots' => $highSlots,
                            'midSlots' => $midSlots,
                            'lowSlots' => $lowSlots,
                            'rigSlots' => $rigSlots,
                            'subSystems' => $subSystems,
                            'serviceSlots' => $serviceSlots
                        ]);
                    }

                    /**
                     * Show service buttons
                     */
                    TemplateHelper::getInstance()->getTemplate('fitting-details/utilities/fitting-service-buttons', [
                        'shipID' => $shipID,
                        'fittingDna' => $fittingDna,
                        'eftFitting' => $eftFitting,
                        'pluginSettings' => $pluginSettings
                    ]);

                    /**
                     * Show market prices
                     */
                    if(isset($pluginSettings['template-detail-parts-settings']['show-market-data']) && $pluginSettings['template-detail-parts-settings']['show-market-data'] === 'yes') {
                        TemplateHelper::getInstance()->getTemplate('fitting-details/information/fitting-market-prices', [
                            'eftFitting' => $eftFitting
                        ]);
                    }

                    /**
                     * Show doctrines that fitting is used in
                     */
                    if(isset($pluginSettings['template-detail-parts-settings']['show-doctrines']) && $pluginSettings['template-detail-parts-settings']['show-doctrines'] === 'yes') {
                        TemplateHelper::getInstance()->getTemplate('fitting-details/information/fitting-doctrine-usage');
                    }
                    ?>
                </div>

                <div class="col-lg-5 col-xl-6 ship-fitting-information">
                    <?php
                    /**
                     * Show information tabs
                     */
                    TemplateHelper::getInstance()->getTemplate('fitting-details/information/fitting-information-tabs', [
                        'pluginSettings' => $pluginSettings,
                        'eftFitting' => $eftFitting,
                        'shipID' => $shipID
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </section>
</article><!-- /.post-->
