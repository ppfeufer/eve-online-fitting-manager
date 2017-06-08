<?php

namespace WordPress\Plugin\EveOnlineFittingManager\Libs;

use WordPress\Plugin\EveOnlineFittingManager;

\defined('ABSPATH') or die();

class MetaBoxes {
	public function __construct() {
		\add_action('add_meta_boxes', array($this, 'registerMetaBoxes'));
		\add_action('save_post', array($this, 'saveMetaBoxes'));
	} // END public function __construct()

	public function registerMetaBoxes() {
		\add_meta_box('eve-online-fitting-manager_eft-fitting', \__('EFT Fitting', 'eve-online-fitting-manager'), array($this, 'renderEftFittingMetaBox'), 'fitting', 'normal');
	} // END public function registerMetaBoxes()

	public function renderEftFittingMetaBox($post) {
		$eftFitting = \get_post_meta($post->ID, 'eve-online-fitting-manager_eft-import', true);
		?>
		<p class="checkbox-wrapper">
			<textarea id="eve-online-fitting-manager_eft-import" name="eve-online-fitting-manager_eft-import" style="width: 100%; height: 250px;"><?php echo $eftFitting; ?></textarea>
		</p>
		<?php

		\wp_nonce_field('save', '_eve-online-fitting-manager_nonce');
	} // END public function renderEftFittingMetaBox($post)

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

		$eftFitting = \filter_input(\INPUT_POST, 'eve-online-fitting-manager_eft-import');

		$shipType = EveOnlineFittingManager\Helper\EftHelper::getShipType($eftFitting);
		$shipName = EveOnlineFittingManager\Helper\EftHelper::getFittingName($eftFitting);
		$shipID = EveOnlineFittingManager\Helper\FittingHelper::getItemIdByName($shipName);

		$fittingSlotData = EveOnlineFittingManager\Helper\EftHelper::getSlotDataFromEftData($eftFitting);
		$fittingDna = EveOnlineFittingManager\Helper\EftHelper::getShipDnaFromEftData($eftFitting);

		\update_post_meta($postID, 'eve-online-fitting-manager_eft-import', $eftFitting);
		\update_post_meta($postID, 'eve-online-fitting-manager_ship_type', $shipType);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_name', $shipName);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_ship_ID', $shipID);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_high_slots', (!empty($fittingSlotData['highSlots'])) ? \serialize($fittingSlotData['highSlots']) : null);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_mid_slots', (!empty($fittingSlotData['midSlots'])) ? \serialize($fittingSlotData['midSlots']) : null);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_low_slots', (!empty($fittingSlotData['lowSlots'])) ? \serialize($fittingSlotData['lowSlots']) : null);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_rig_slots', (!empty($fittingSlotData['rigSlots'])) ? \serialize($fittingSlotData['rigSlots']) : null);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_subsystems', (!empty($fittingSlotData['subSystems'])) ? \serialize($fittingSlotData['subSystems']) : null);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_drones', (!empty($fittingSlotData['drones'])) ? \serialize($fittingSlotData['drones']) : null);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_charges', (!empty($fittingSlotData['charges'])) ? \serialize($fittingSlotData['charges']) : null);
		\update_post_meta($postID, 'eve-online-fitting-manager_fitting_dna', $fittingDna);
	} // END public function saveMetaBoxes($postID)
} // END class MetaBoxes
