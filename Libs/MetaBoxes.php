<?php

namespace WordPress\Plugin\EveOnlineFittingManager\Libs;

use WordPress\Plugin\EveOnlineFittingManager;

\defined('ABSPATH') or die();

/**
 * Add some Meta Boxes to our fitting edit page in the WordPress dashboard
 */
class MetaBoxes {
	/**
	 * Constructor
	 */
	public function __construct() {
		\add_action('add_meta_boxes', array($this, 'registerMetaBoxes'));
		\add_action('save_post', array($this, 'saveMetaBoxes'));
	} // END public function __construct()

	/**
	 * Registering the Meta Boxes
	 */
	public function registerMetaBoxes() {
		\add_meta_box('eve-online-fitting-manager_eft-fitting', \__('EFT Fitting', 'eve-online-fitting-manager'), array($this, 'renderEftFittingMetaBox'), 'fitting', 'normal');
		\add_meta_box('eve-online-fitting-manager_fitting-marker', \__('Mark Fitting As ...', 'eve-online-fitting-manager'), array($this, 'renderFittingMarkerMetaBox'), 'fitting', 'side');
	} // END public function registerMetaBoxes()

	/**
	 * Rendering the "EFT Fitting" Meta Box
	 *
	 * @global string $typenow
	 * @param object $post The post object of the current post
	 */
	public function renderEftFittingMetaBox($post) {
		global $typenow;

		$eftFitting = null;

		if(PostType::isEditPage('edit') && $typenow === 'fitting') {
			if(\get_post_meta($post->ID, 'eve-online-fitting-manager_fitting_ship_ID', true) !== null) {
				$eftFitting = EveOnlineFittingManager\Helper\EftHelper::getEftImportFromFitting(array(
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
				));
			} // END if(\get_post_meta($post->ID, 'eve-online-fitting-manager_fitting_ship_ID', true) !== null)
		} // END if(PostType::isEditPage('edit') && $typenow === 'fitting')
		?>
		<p class="checkbox-wrapper">
			<textarea id="eve-online-fitting-manager_eft-import" name="eve-online-fitting-manager_eft-import" style="width: 100%; height: 250px;"><?php echo $eftFitting; ?></textarea>
		</p>
		<?php

		\wp_nonce_field('save', '_eve-online-fitting-manager_nonce');
	} // END public function renderEftFittingMetaBox($post)

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

		if(PostType::isEditPage('edit') && $typenow === 'fitting') {
			$isConcept = \get_post_meta($post->ID, 'eve-online-fitting-manager_fitting_is_concept', true);
			$isIdea = \get_post_meta($post->ID, 'eve-online-fitting-manager_fitting_is_idea', true);
			$isUnderDiscussion = \get_post_meta($post->ID, 'eve-online-fitting-manager_fitting_is_under_discussion', true);
		} // END if(PostType::isEditPage('edit') && $typenow === 'fitting')
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
	} // END public function renderFittingMarkerMetaBox($post)

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
		} // END if(empty($postNonce) || !\wp_verify_nonce($postNonce, 'save'))

		if(!\current_user_can('edit_post', $postID)) {
			return false;
		} // END if(!\current_user_can('edit_post', $postID))

		if(\defined('DOING_AJAX')) {
			return false;
		} // END if(defined('DOING_AJAX'))

		// EFT Fitting
		$eftFitting = \filter_input(\INPUT_POST, 'eve-online-fitting-manager_eft-import');

		$shipType = EveOnlineFittingManager\Helper\EftHelper::getShipType($eftFitting);
		$shipName = EveOnlineFittingManager\Helper\EftHelper::getFittingName($eftFitting);
		$shipID = EveOnlineFittingManager\Helper\FittingHelper::getItemIdByName($shipType);

		$fittingSlotData = EveOnlineFittingManager\Helper\EftHelper::getSlotDataFromEftData($eftFitting);
		$fittingDna = EveOnlineFittingManager\Helper\EftHelper::getShipDnaFromEftData($eftFitting);

		\update_post_meta($postID, 'eve-online-fitting-manager_ship_type', (!empty($shipType)) ? $shipType : null);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_name', (!empty($shipName)) ? $shipName : null);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_ship_ID', (!empty($shipID)) ? $shipID : null);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_high_slots', (!empty($fittingSlotData['highSlots'])) ? \serialize($fittingSlotData['highSlots']) : null);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_mid_slots', (!empty($fittingSlotData['midSlots'])) ? \serialize($fittingSlotData['midSlots']) : null);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_low_slots', (!empty($fittingSlotData['lowSlots'])) ? \serialize($fittingSlotData['lowSlots']) : null);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_rig_slots', (!empty($fittingSlotData['rigSlots'])) ? \serialize($fittingSlotData['rigSlots']) : null);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_subsystems', (!empty($fittingSlotData['subSystems'])) ? \serialize($fittingSlotData['subSystems']) : null);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_upwellservices', (!empty($fittingSlotData['upwellServices'])) ? \serialize($fittingSlotData['upwellServices']) : null);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_drones', (!empty($fittingSlotData['drones'])) ? \serialize($fittingSlotData['drones']) : null);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_charges', (!empty($fittingSlotData['charges'])) ? \serialize($fittingSlotData['charges']) : null);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_fuel', (!empty($fittingSlotData['fuel'])) ? \serialize($fittingSlotData['fuel']) : null);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_dna', (!empty($fittingDna)) ? $fittingDna : null);

		// Mark Fitting As
		$isConcept = \filter_input(INPUT_POST, 'eve-online-fitting-manager_fitting_is_concept') === 'on';
		$isIdea = \filter_input(INPUT_POST, 'eve-online-fitting-manager_fitting_is_idea') === 'on';
		$isUnderDiscussion = \filter_input(INPUT_POST, 'eve-online-fitting-manager_fitting_is_under_discussion') === 'on';

		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_is_concept', $isConcept);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_is_idea', $isIdea);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_is_under_discussion', $isUnderDiscussion);
	} // END public function saveMetaBoxes($postID)
} // END class MetaBoxes
