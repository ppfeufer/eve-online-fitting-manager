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
use \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\ImageHelper;
use \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\PluginHelper;

$fittingSlotLayout = FittingHelper::getInstance()->getSlotLayoutFromFittingArray([
    'shipID' => $shipID,
    'highSlots' => $highSlots,
    'midSlots' => $midSlots,
    'lowSlots' => $lowSlots,
    'rigSlots' => $rigSlots,
    'subSystems' => $subSystems,
    'serviceSlots' => $serviceSlots
]);

$itemsHighSlots = null;
if(!empty($highSlots)) {
    $itemsHighSlots = \maybe_unserialize($highSlots);
}

$itemsMidSlots = null;
if(!empty($midSlots)) {
    $itemsMidSlots = \maybe_unserialize($midSlots);
}

$itemsLowSlots = null;
if(!empty($lowSlots)) {
    $itemsLowSlots = \maybe_unserialize($lowSlots);
}

$itemsRigSlots = null;
if(!empty($rigSlots)) {
    $itemsRigSlots = \maybe_unserialize($rigSlots);
}

$itemsSubSystems = null;
if(!empty($subSystems)) {
    $itemsSubSystems = \maybe_unserialize($subSystems);
}

$itemsServiceSlots = null;
if(!empty($serviceSlots)) {
    $itemsServiceSlots = \maybe_unserialize($serviceSlots);
}
?>

<div class="fittingPanel template-fitting-ring">
    <div class="fittingRing">
        <img alt="" src="<?php echo PluginHelper::getInstance()->getPluginUri('images/fitting-ring/eve-fitting-ring.png'); ?>" class="fittingRingImage">
    </div>

    <!--
    // High Slots
    -->
    <div class="highSlots">
        <img alt="" src="<?php echo PluginHelper::getInstance()->getPluginUri('images/fitting-ring/' . $fittingSlotLayout['highSlots'] . 'h.png'); ?>">
    </div>
    <?php
    if($itemsHighSlots !== null) {
        $hsCount = 1;
        foreach($itemsHighSlots as $highSlotItemID) {
            if(!empty($highSlotItemID)) {
                ?>
                <div class="highSlot_<?php echo $hsCount; ?>" data-toggle="tooltip" data-title="<?php echo FittingHelper::getInstance()->getItemNameById($highSlotItemID); ?>" data-placement="top">
                    <img width="32" height="32" src="<?php echo ImageHelper::getInstance()->getImageServerUrl('item') . $highSlotItemID . '_32.png'; ?>" class="img-rounded">
                </div>
                <?php
            }

            $hsCount++;
        }
    }
    ?>

    <!--
    // Mid Slots
    -->
    <div class="midSlots">
        <img alt="" src="<?php echo PluginHelper::getInstance()->getPluginUri('images/fitting-ring/' . $fittingSlotLayout['midSlots'] . 'm.png'); ?>">
    </div>
    <?php
    if($itemsMidSlots !== null) {
        $msCount = 1;
        foreach($itemsMidSlots as $midSlotItemID) {
            if(!empty($midSlotItemID)) {
                ?>
                <div class="midSlot_<?php echo $msCount; ?>" data-toggle="tooltip" data-title="<?php echo FittingHelper::getInstance()->getItemNameById($midSlotItemID); ?>" data-placement="top">
                    <img width="32" height="32" src="<?php echo ImageHelper::getInstance()->getImageServerUrl('item') . $midSlotItemID . '_32.png'; ?>" class="img-rounded">
                </div>
                <?php
            }

            $msCount++;
        }
    }
    ?>

    <!--
    // Low Slots
    -->
    <div class="lowSlots">
        <img style="border: 0px;" alt="" src="<?php echo PluginHelper::getInstance()->getPluginUri('images/fitting-ring/' . $fittingSlotLayout['lowSlots'] . 'l.png'); ?>">
    </div>
    <?php
    if($itemsLowSlots !== null) {
        $lsCount = 1;

        foreach($itemsLowSlots as $lowSlotItemID) {
            if(!empty($lowSlotItemID)) {
                ?>
                <div class="lowSlot_<?php echo $lsCount; ?>" data-toggle="tooltip" data-title="<?php echo FittingHelper::getInstance()->getItemNameById($lowSlotItemID); ?>" data-placement="top">
                    <img width="32" height="32" src="<?php echo ImageHelper::getInstance()->getImageServerUrl('item') . $lowSlotItemID . '_32.png'; ?>" class="img-rounded">
                </div>
                <?php
            }

            $lsCount++;
        }
    }
    ?>

    <!--
    // Rig Slots
    -->
    <div class="rigSlots">
        <img style="border: 0px;" alt="" src="<?php echo PluginHelper::getInstance()->getPluginUri('images/fitting-ring/' . $fittingSlotLayout['rigSlots'] . 'r.png'); ?>">
    </div>
    <?php
    if($itemsRigSlots !== null) {
        $rsCount = 1;

        foreach($itemsRigSlots as $rigSlotItemID) {
            if(!empty($rigSlotItemID)) {
                ?>
                <div class="rigSlot_<?php echo $rsCount; ?>" data-toggle="tooltip" data-title="<?php echo FittingHelper::getInstance()->getItemNameById($rigSlotItemID); ?>" data-placement="top">
                    <img width="32" height="32" src="<?php echo ImageHelper::getInstance()->getImageServerUrl('item') . $rigSlotItemID . '_32.png'; ?>" class="img-rounded">
                </div>
                <?php
            }

            $rsCount++;
        }
    }

    if($itemsSubSystems !== null) {
        ?>
        <!--
        // Subsystems
        -->
        <div class="subSystems">
            <img style="border: 0px;" alt="" src="<?php echo PluginHelper::getInstance()->getPluginUri('images/fitting-ring/4s.png'); ?>">
        </div>
        <?php
        $ssCount = 1;

        foreach($itemsSubSystems as $subSystemItemID) {
            if(!empty($subSystemItemID)) {
                ?>
                <div class="subSystem_<?php echo $ssCount; ?>" data-toggle="tooltip" data-title="<?php echo FittingHelper::getInstance()->getItemNameById($subSystemItemID); ?>" data-placement="top">
                    <img width="32" height="32" src="<?php echo ImageHelper::getInstance()->getImageServerUrl('item') . $subSystemItemID . '_32.png'; ?>" class="img-rounded">
                </div>
                <?php
            }

            $ssCount++;
        }
    }

    if($itemsServiceSlots !== null) {
        ?>
        <!--
        // Upwell Services
        -->
        <div class="serviceSlots">
            <img style="border: 0px;" alt="" src="<?php echo PluginHelper::getInstance()->getPluginUri('images/fitting-ring/5s.png'); ?>">
        </div>
        <?php
        $ssCount = 1;

        foreach($itemsServiceSlots as $serviceSlotItemID) {
            if(!empty($serviceSlotItemID)) {
                ?>
                <div class="serviceSlot_<?php echo $ssCount; ?>" data-toggle="tooltip" data-title="<?php echo FittingHelper::getInstance()->getItemNameById($serviceSlotItemID); ?>" data-placement="top">
                    <img width="32" height="32" src="<?php echo ImageHelper::getInstance()->getImageServerUrl('item') . $serviceSlotItemID . '_32.png'; ?>" class="img-rounded">
                </div>
                <?php
            }

            $ssCount++;
        }
    }
    ?>

    <!--
    // Ship
    -->
    <div class="shipImage">
        <img width="256" height="256" class="eveimage img-rounded" src="<?php echo ImageHelper::getInstance()->getImageServerUrl('ship') . $shipID . '_256.png'; ?>">
    </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
    $(function() {
        $('[data-toggle="tooltip"]').tooltip();
    })
});
</script>
