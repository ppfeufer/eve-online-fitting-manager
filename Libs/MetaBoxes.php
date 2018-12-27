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

namespace WordPress\Plugins\EveOnlineFittingManager\Libs;

use \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\EftHelper;
use \WordPress\Plugins\EveOnlineFittingManager\Libs\Helper\FittingHelper;

\defined('ABSPATH') or die();

/**
 * Add some Meta Boxes to our fitting edit page in the WordPress dashboard
 */
class MetaBoxes {
    /**
     * Constructor
     */
    public function __construct() {
        \add_action('add_meta_boxes', [$this, 'registerMetaBoxes']);
        \add_action('save_post', [$this, 'saveMetaBoxes']);
    }

    /**
     * Registering the Meta Boxes
     */
    public function registerMetaBoxes() {
        \add_meta_box('eve-online-fitting-manager_eft-fitting', \__('EFT Fitting', 'eve-online-fitting-manager'), [$this, 'renderEftFittingMetaBox'], 'fitting', 'normal');
        \add_meta_box('eve-online-fitting-manager_fitting-marker', \__('Mark Fitting As ...', 'eve-online-fitting-manager'), [$this, 'renderFittingMarkerMetaBox'], 'fitting', 'side');
    }

    /**
     * Rendering the "EFT Fitting" Meta Box
     *
     * @global string $typenow
     * @param object $post The post object of the current post
     */
    public function renderEftFittingMetaBox($post) {
        global $typenow;

        $eftFitting = null;

        if(PostType::getInstance()->isEditPage('edit') && $typenow === 'fitting') {
            if(\get_post_meta($post->ID, 'eve-online-fitting-manager_fitting_ship_ID', true) !== null) {
                $eftFitting = EftHelper::getInstance()->getEftImportFromFitting([
                    'shipID' => \get_post_meta($post->ID, 'eve-online-fitting-manager_fitting_ship_ID', true),
                    'fittingType' => \get_post_meta($post->ID, 'eve-online-fitting-manager_fitting_name', true),
                    'highSlots' => \get_post_meta($post->ID, 'eve-online-fitting-manager_fitting_high_slots', true),
                    'midSlots' => \get_post_meta($post->ID, 'eve-online-fitting-manager_fitting_mid_slots', true),
                    'lowSlots' => \get_post_meta($post->ID, 'eve-online-fitting-manager_fitting_low_slots', true),
                    'rigSlots' => \get_post_meta($post->ID, 'eve-online-fitting-manager_fitting_rig_slots', true),
                    'subSystems' => \get_post_meta($post->ID, 'eve-online-fitting-manager_fitting_subsystems', true),
                    'serviceSlots' => \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_upwellservices', true),
                    'drones' => \get_post_meta($post->ID, 'eve-online-fitting-manager_fitting_drones', true),
                    'charges' => \get_post_meta($post->ID, 'eve-online-fitting-manager_fitting_charges', true),
                    'fuel' => \get_post_meta($post->ID, 'eve-online-fitting-manager_fitting_fuel', true),
                    'implantsAndBooster' => \get_post_meta($post->ID, 'eve-online-fitting-manager_fitting_implants_and_booster', true),
                ]);
            }
        }
        ?>
        <p class="checkbox-wrapper">
            <textarea id="eve-online-fitting-manager_eft-import" name="eve-online-fitting-manager_eft-import" style="width: 100%; height: 250px;"><?php echo $eftFitting; ?></textarea>
        </p>
        <?php
        \wp_nonce_field('save', '_eve-online-fitting-manager_nonce');
    }

    /**
     * Rendering the "Mark Fitting as" Meta Box
     *
     * @global string $typenow
     * @param object $post The post object of the current post
     */
    public function renderFittingMarkerMetaBox($post) {
        global $typenow;

        $isConcept = null;
        $isIdea = null;
        $isUnderDiscussion = null;

        if(PostType::getInstance()->isEditPage('edit') && $typenow === 'fitting') {
            $isConcept = \get_post_meta($post->ID, 'eve-online-fitting-manager_fitting_is_concept', true);
            $isIdea = \get_post_meta($post->ID, 'eve-online-fitting-manager_fitting_is_idea', true);
            $isUnderDiscussion = \get_post_meta($post->ID, 'eve-online-fitting-manager_fitting_is_under_discussion', true);
        }
        ?>
        <p class="checkbox-wrapper">
            <input id="eve-online-fitting-manager_fitting_is_concept" name="eve-online-fitting-manager_fitting_is_concept" type="checkbox" <?php \checked($isConcept); ?>>
            <label for="eve-online-fitting-manager_fitting_is_concept"><?php \_e('Conceptual Fitting', 'eve-online-fitting-manager'); ?></label>
        </p>
        <p class="checkbox-wrapper">
            <input id="eve-online-fitting-manager_fitting_is_idea" name="eve-online-fitting-manager_fitting_is_idea" type="checkbox" <?php \checked($isIdea); ?>>
            <label for="eve-online-fitting-manager_fitting_is_idea"><?php \_e('Fitting Idea', 'eve-online-fitting-manager'); ?></label>
        </p>
        <p class="checkbox-wrapper">
            <input id="eve-online-fitting-manager_fitting_is_under_discussion" name="eve-online-fitting-manager_fitting_is_under_discussion" type="checkbox" <?php \checked($isUnderDiscussion); ?>>
            <label for="eve-online-fitting-manager_fitting_is_under_discussion"><?php \_e('Under Duscussion', 'eve-online-fitting-manager'); ?></label>
        </p>
        <?php
    }

    /**
     * Save the meta data
     *
     * @param int $postID ID of the current post
     * @return boolean
     */
    public function saveMetaBoxes($postID) {
        $postNonce = \filter_input(\INPUT_POST, '_eve-online-fitting-manager_nonce');

        if(empty($postNonce) || !\wp_verify_nonce($postNonce, 'save')) {
            return false;
        }

        if(!\current_user_can('edit_post', $postID)) {
            return false;
        }

        if(\defined('DOING_AJAX')) {
            return false;
        }

        // EFT Fitting
        $eftFitting = \filter_input(\INPUT_POST, 'eve-online-fitting-manager_eft-import');
        $shipType = EftHelper::getInstance()->getShipType($eftFitting);
        $shipName = EftHelper::getInstance()->getFittingName($eftFitting);
        $shipID = FittingHelper::getInstance()->getItemIdByName($shipType, 'inventoryTypes');
        $fittingSlotData = EftHelper::getInstance()->getSlotDataFromEftData($eftFitting);
        $fittingDna = EftHelper::getInstance()->getShipDnaFromEftData($eftFitting);

        \update_post_meta($postID, 'eve-online-fitting-manager_ship_type', (!empty($shipType)) ? \maybe_serialize($shipType) : null);
        \update_post_meta($postID, 'eve-online-fitting-manager_fitting_name', (!empty($shipName)) ? \maybe_serialize($shipName) : null);
        \update_post_meta($postID, 'eve-online-fitting-manager_fitting_ship_ID', (!empty($shipID)) ? \maybe_serialize($shipID) : null);
        \update_post_meta($postID, 'eve-online-fitting-manager_fitting_high_slots', (!empty($fittingSlotData['highSlots'])) ? \maybe_serialize($fittingSlotData['highSlots']) : null);
        \update_post_meta($postID, 'eve-online-fitting-manager_fitting_mid_slots', (!empty($fittingSlotData['midSlots'])) ? \maybe_serialize($fittingSlotData['midSlots']) : null);
        \update_post_meta($postID, 'eve-online-fitting-manager_fitting_low_slots', (!empty($fittingSlotData['lowSlots'])) ? \maybe_serialize($fittingSlotData['lowSlots']) : null);
        \update_post_meta($postID, 'eve-online-fitting-manager_fitting_rig_slots', (!empty($fittingSlotData['rigSlots'])) ? \maybe_serialize($fittingSlotData['rigSlots']) : null);
        \update_post_meta($postID, 'eve-online-fitting-manager_fitting_subsystems', (!empty($fittingSlotData['subSystems'])) ? \maybe_serialize($fittingSlotData['subSystems']) : null);
        \update_post_meta($postID, 'eve-online-fitting-manager_fitting_upwellservices', (!empty($fittingSlotData['upwellServices'])) ? \maybe_serialize($fittingSlotData['upwellServices']) : null);
        \update_post_meta($postID, 'eve-online-fitting-manager_fitting_drones', (!empty($fittingSlotData['drones'])) ? \maybe_serialize($fittingSlotData['drones']) : null);
        \update_post_meta($postID, 'eve-online-fitting-manager_fitting_charges', (!empty($fittingSlotData['charges'])) ? \maybe_serialize($fittingSlotData['charges']) : null);
        \update_post_meta($postID, 'eve-online-fitting-manager_fitting_fuel', (!empty($fittingSlotData['fuel'])) ? \maybe_serialize($fittingSlotData['fuel']) : null);
        \update_post_meta($postID, 'eve-online-fitting-manager_fitting_implants_and_booster', (!empty($fittingSlotData['implantsAndBooster'])) ? \maybe_serialize($fittingSlotData['implantsAndBooster']) : null);
        \update_post_meta($postID, 'eve-online-fitting-manager_fitting_dna', (!empty($fittingDna)) ? \maybe_serialize($fittingDna) : null);

        // Mark Fitting As
        $isConcept = \filter_input(\INPUT_POST, 'eve-online-fitting-manager_fitting_is_concept') === 'on';
        $isIdea = \filter_input(\INPUT_POST, 'eve-online-fitting-manager_fitting_is_idea') === 'on';
        $isUnderDiscussion = \filter_input(\INPUT_POST, 'eve-online-fitting-manager_fitting_is_under_discussion') === 'on';

        \update_post_meta($postID, 'eve-online-fitting-manager_fitting_is_concept', \maybe_serialize($isConcept));
        \update_post_meta($postID, 'eve-online-fitting-manager_fitting_is_idea', \maybe_serialize($isIdea));
        \update_post_meta($postID, 'eve-online-fitting-manager_fitting_is_under_discussion', \maybe_serialize($isUnderDiscussion));
    }
}
