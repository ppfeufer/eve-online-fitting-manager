<?php
defined('ABSPATH') or die();

// Let's get seom variables sorted first
$pluginSettings = \WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginSettings();
$shipID = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_ship_ID', true);
$fittingType = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_name', true);
$highSlots = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_high_slots', true);
$midSlots = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_mid_slots', true);
$lowSlots = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_low_slots', true);
$rigSlots = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_rig_slots', true);
$subSystems = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_subsystems', true);
$serviceSlots = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_upwellservices', true);
$drones = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_drones', true);
$charges = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_charges', true);
$fuel = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_fuel', true);
$fittingDna = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_dna', true);
$eftFitting = \WordPress\Plugin\EveOnlineFittingManager\Helper\EftHelper::getEftImportFromFitting(array(
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
	'fuel' => $fuel
));
?>

<header class="entry-header">
	<h1 class="entry-title">
		<img src="<?php echo \WordPress\Plugin\EveOnlineFittingManager\Helper\FittingHelper::getShipImageById(\get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_ship_ID', true), 64); ?>">
		<?php \the_title(); ?>
	</h1>

	<aside class="entry-details">
		<p class="meta">
			<?php \edit_post_link(\__('Edit', 'eve-online-fitting-manager')); ?>
		</p>
	</aside><!--end .entry-details -->
</header><!--end .entry-header -->

<article id="post-<?php \the_ID(); ?>" <?php \post_class('clearfix content-single template-content-single-fitting'); ?>>
	<section class="post-content">
		<div class="entry-content">
			<?php
			/**
			 * Show doctrines tha fitting is used in
			 */
			\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting-details/information/fitting-marker');
			?>

			<div class="row">
				<div class="col-lg-7 col-xl-6 ship-fitting-visualization">
					<?php
					/**
					 * Show visual fitting
					 */
					if(isset($pluginSettings['template-detail-parts-settings']['show-visual-fitting']) && $pluginSettings['template-detail-parts-settings']['show-visual-fitting'] === 'yes') {
						\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting-details/visualization/fitting-ring', array(
							'shipID' => $shipID,
							'highSlots' => $highSlots,
							'midSlots' => $midSlots,
							'lowSlots' => $lowSlots,
							'rigSlots' => $rigSlots,
							'subSystems' => $subSystems,
							'serviceSlots' => $serviceSlots
						));
					} // END if(!isset($pluginSettings['template-detail-parts-settings']['show-visual-fitting']) && $pluginSettings['template-detail-parts-settings']['show-visual-fitting'] === 'yes')

					if(isset($pluginSettings['template-detail-parts-settings']['show-osmium-link']) && $pluginSettings['template-detail-parts-settings']['show-osmium-link'] === 'yes') {
						\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting-details/utilities/fitting-service-buttons', array(
							'shipID' => $shipID,
							'fittingDna' => $fittingDna,
							'eftFitting' => $eftFitting,
							'pluginSettings' => $pluginSettings
						));
					} // END if(isset($pluginSettings['template-detail-parts-settings']['show-osmium-link']) && $pluginSettings['template-detail-parts-settings']['show-osmium-link'] === 'yes')
					/**
					 * Show the Osmium fitting link
					 */
//					if(isset($pluginSettings['template-detail-parts-settings']['show-osmium-link']) && $pluginSettings['template-detail-parts-settings']['show-osmium-link'] === 'yes') {
//						\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting-details/utilities/fitting-osmium', array(
//							'shipID' => $shipID,
//							'fittingDna' => $fittingDna
//						));
//					} // END if(isset($pluginSettings['template-detail-parts-settings']['show-osmium-link']) && $pluginSettings['template-detail-parts-settings']['show-osmium-link'] === 'yes')

					/**
					 * Show copy eft data to clipboard button
					 */
//					if(isset($pluginSettings['template-detail-parts-settings']['show-copy-eft']) && $pluginSettings['template-detail-parts-settings']['show-copy-eft'] === 'yes') {
//						\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting-details/utilities/fitting-copy-eft-to-clipboard', array(
//							'eftFitting' => $eftFitting
//						));
//					} // END if(isset($pluginSettings['template-detail-parts-settings']['show-copy-eft']) && $pluginSettings['template-detail-parts-settings']['show-copy-eft'] === 'yes')

					/**
					 * Show copy permalink to clipboard button
					 */
//					if(isset($pluginSettings['template-detail-parts-settings']['show-copy-permalink']) && $pluginSettings['template-detail-parts-settings']['show-copy-permalink'] === 'yes') {
//						\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting-details/utilities/fitting-copy-permalink-to-clipboard');
//					} // END if(isset($pluginSettings['template-detail-parts-settings']['show-copy-permalink']) && $pluginSettings['template-detail-parts-settings']['show-copy-permalink'] === 'yes')

					/**
					 * Show Market Prices
					 */
					if(isset($pluginSettings['template-detail-parts-settings']['show-market-data']) && $pluginSettings['template-detail-parts-settings']['show-market-data'] === 'yes') {
						\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting-details/information/fitting-market-prices', array(
							'eftFitting' => $eftFitting
						));
					} // END if(isset($pluginSettings['template-detail-parts-settings']['show-market-data']) && $pluginSettings['template-detail-parts-settings']['show-market-data'] === 'yes')

					/**
					 * Show doctrines that fitting is used in
					 */
					if(isset($pluginSettings['template-detail-parts-settings']['show-doctrines']) && $pluginSettings['template-detail-parts-settings']['show-doctrines'] === 'yes') {
						\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting-details/information/fitting-doctrine-usage');
					} // END if(isset($pluginSettings['template-detail-parts-settings']['show-doctrines']) && $pluginSettings['template-detail-parts-settings']['show-doctrines'] === 'yes')
					?>
				</div>

				<div class="col-lg-5 col-xl-6 ship-fitting-information">
					<?php
					/**
					 * Show information tabs
					 */
					\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting-details/information/fitting-information-tabs', array(
						'pluginSettings' => $pluginSettings,
						'eftFitting' => $eftFitting,
						'shipID' => $shipID
					));
					?>
				</div>
			</div>
		</div>
	</section>
</article><!-- /.post-->
