<?php
defined('ABSPATH') or die();

// Let's get seom variables sorted first
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
					 * Show the fitting ring
					 */
					\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting-details/visualization/fitting-ring', array(
						'shipID' => $shipID,
						'highSlots' => $highSlots,
						'midSlots' => $midSlots,
						'lowSlots' => $lowSlots,
						'rigSlots' => $rigSlots,
						'subSystems' => $subSystems,
						'serviceSlots' => $serviceSlots
					));

					/**
					 * Show the Osmium fitting link
					 */
					\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting-details/utilities/fitting-osmium', array(
						'shipID' => $shipID,
						'fittingDna' => $fittingDna
					));

					/**
					 * Show copy eft data to clipboard button
					 */
					\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting-details/utilities/fitting-copy-eft-to-clipboard', array(
						'eftFitting' => $eftFitting
					));

					/**
					 * Show copy permalink to clipboard button
					 */
					\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting-details/utilities/fitting-copy-permalink-to-clipboard');

					/**
					 * Show Market Prices
					 */
					\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting-details/information/fitting-market-prices', array(
						'eftFitting' => $eftFitting
					));

					/**
					 * Show doctrines tha fitting is used in
					 */
					\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting-details/information/fitting-doctrine-usage');
					?>
				</div>

				<div class="col-lg-5 col-xl-6 ship-fitting-information">
					<?php
					/**
					 * Show information tabs
					 */
					\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting-details/information/fitting-information-tabs', array(
						'eftFitting' => $eftFitting,
						'shipID' => $shipID
					));
					?>
				</div>
			</div>
		</div>
	</section>
</article><!-- /.post-->
