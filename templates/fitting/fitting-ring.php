<?php
$shipID = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_ship_ID', true);
$highSlots = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_high_slots', true);
$midSlots = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_mid_slots', true);
$lowSlots = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_low_slots', true);
$rigSlots = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_rig_slots', true);
$subSystems = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_subsystems', true);
$fittingDna = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_dna', true);

$fittingSlotLayout = WordPress\Plugin\EveOnlineFittingManager\Helper\FittingHelper::getSlotLayoutFromFittingArray(array(
	'shipID' => $shipID,
	'highSlots' => $highSlots,
	'midSlots' => $midSlots,
	'lowSlots' => $lowSlots,
	'rigSlots' => $rigSlots,
	'subSystems' => $subSystems
));

$itemsHighSlots = null;
if($highSlots !== null) {
	$itemsHighSlots = unserialize($highSlots);
}

$itemsMidSlots = null;
if($midSlots !== null) {
	$itemsMidSlots = unserialize($midSlots);
}

$itemsLowSlots = null;
if($lowSlots !== null) {
	$itemsLowSlots = unserialize($lowSlots);
}

$itemsRigSlots = null;
if($rigSlots !== null) {
	$itemsRigSlots = unserialize($rigSlots);
}

$itemsSubSystems = null;
if($subSystems !== null) {
	$itemsSubSystems = unserialize($subSystems);
}
?>

<div class="fittingPanel">
	<div class="fittingRing">
		<img alt="" src="<?php echo WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('images/fitting-ring/eve-fitting-ring.png'); ?>" class="fittingRingImage">
	</div>

	<!--
	// High Slots
	-->
	<div class="highSlots">
		<img alt="" src="<?php echo WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('images/fitting-ring/' . $fittingSlotLayout['highSlots'] . 'h.png'); ?>">
	</div>
	<?php
	if($itemsHighSlots !== null) {
		$hsCount = 1;
		foreach($itemsHighSlots as $highSlotItemID) {
			if(!empty($highSlotItemID)) {
				?>
				<div class="highSlot_<?php echo $hsCount; ?>" data-toggle="tooltip" data-title="<?php echo WordPress\Plugin\EveOnlineFittingManager\Helper\FittingHelper::getItemNameById($highSlotItemID); ?>" data-placement="top">
					<img width="32" height="32" src="<?php echo WordPress\Plugin\EveOnlineFittingManager\Helper\ImageHelper::getLocalCacheImageUriForRemoteImage('item', 'https://imageserver.eveonline.com/Type/' . $highSlotItemID . '_32.png')?>" class="img-rounded">
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
		<img alt="" src="<?php echo WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('images/fitting-ring/' . $fittingSlotLayout['midSlots'] . 'm.png'); ?>">
	</div>
	<?php
	if($itemsMidSlots !== null) {
		$msCount = 1;
		foreach($itemsMidSlots as $midSlotItemID) {
			if(!empty($midSlotItemID)) {
				?>
				<div class="midSlot_<?php echo $msCount; ?>" data-toggle="tooltip" data-title="<?php echo WordPress\Plugin\EveOnlineFittingManager\Helper\FittingHelper::getItemNameById($midSlotItemID); ?>" data-placement="top">
					<img width="32" height="32" src="<?php echo WordPress\Plugin\EveOnlineFittingManager\Helper\ImageHelper::getLocalCacheImageUriForRemoteImage('item', 'https://imageserver.eveonline.com/Type/' . $midSlotItemID . '_32.png')?>" class="img-rounded">
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
		<img style="border: 0px;" alt="" src="<?php echo WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('images/fitting-ring/' . $fittingSlotLayout['lowSlots'] . 'l.png'); ?>">
	</div>
	<?php
	if($itemsLowSlots !== null) {
		$lsCount = 1;
		foreach($itemsLowSlots as $lowSlotItemID) {
			if(!empty($lowSlotItemID)) {
				?>
				<div class="lowSlot_<?php echo $lsCount; ?>" data-toggle="tooltip" data-title="<?php echo WordPress\Plugin\EveOnlineFittingManager\Helper\FittingHelper::getItemNameById($lowSlotItemID); ?>" data-placement="top">
					<img width="32" height="32" src="<?php echo WordPress\Plugin\EveOnlineFittingManager\Helper\ImageHelper::getLocalCacheImageUriForRemoteImage('item', 'https://imageserver.eveonline.com/Type/' . $lowSlotItemID . '_32.png')?>" class="img-rounded">
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
		<img style="border: 0px;" alt="" src="<?php echo WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('images/fitting-ring/' . $fittingSlotLayout['rigSlots'] . 'r.png'); ?>">
	</div>
	<?php
	if($itemsRigSlots !== null) {
		$rsCount = 1;
		foreach($itemsRigSlots as $rigSlotItemID) {
			if(!empty($rigSlotItemID)) {
				?>
				<div class="rigSlot_<?php echo $rsCount; ?>" data-toggle="tooltip" data-title="<?php echo WordPress\Plugin\EveOnlineFittingManager\Helper\FittingHelper::getItemNameById($rigSlotItemID); ?>" data-placement="top">
					<img width="32" height="32" src="<?php echo WordPress\Plugin\EveOnlineFittingManager\Helper\ImageHelper::getLocalCacheImageUriForRemoteImage('item', 'https://imageserver.eveonline.com/Type/' . $rigSlotItemID . '_32.png')?>" class="img-rounded">
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
			<img style="border: 0px;" alt="" src="<?php echo WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('images/fitting-ring/5s.png'); ?>">
		</div>
		<?php
		$ssCount = 1;
		foreach($itemsSubSystems as $subSystemItemID) {
			if(!empty($subSystemItemID)) {
				?>
				<div class="subSystem_<?php echo $ssCount; ?>" data-toggle="tooltip" data-title="<?php echo WordPress\Plugin\EveOnlineFittingManager\Helper\FittingHelper::getItemNameById($subSystemItemID); ?>" data-placement="top">
					<img width="32" height="32" src="<?php echo WordPress\Plugin\EveOnlineFittingManager\Helper\ImageHelper::getLocalCacheImageUriForRemoteImage('item', 'https://imageserver.eveonline.com/Type/' . $subSystemItemID . '_32.png')?>" class="img-rounded">
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
		<img width="256" height="256" alt="Tengu" class="eveimage img-rounded" src="<?php echo WordPress\Plugin\EveOnlineFittingManager\Helper\ImageHelper::getLocalCacheImageUriForRemoteImage('ship', 'https://imageserver.eveonline.com/Render/' . $shipID . '_256.png')?>">
	</div>
</div>

<div class="fitting-view-osmium-loadout">
	<ul class="nav nav-pills nav-stacked">
		<li role="presentation">
			<a href="https://o.smium.org/loadout/dna/<?php echo $fittingDna; ?>" type="button" class="btn btn-default" target="_blank"><?php echo \__('View Loadout @Osmium.org', 'eve-online-fitting-manager') ; ?></a>
		</li>
	</ul>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
	$(function () {
		$('[data-toggle="tooltip"]').tooltip();
	})
});
</script>
