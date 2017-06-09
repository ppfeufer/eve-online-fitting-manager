<?php defined('ABSPATH') or die(); ?>

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

<article id="post-<?php \the_ID(); ?>" <?php \post_class('clearfix content-single content-fitting'); ?>>
	<section class="post-content">
		<div class="entry-content">
			<?php
			if(\is_single()) {
				echo \the_content();
			}

			echo \nl2br(\WordPress\Plugin\EveOnlineFittingManager\Helper\EftHelper::getEftImportFromFitting(array(
				'shipID' => \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_ship_ID', true),
				'fittingType' => \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_name', true),
				'highSlots' => \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_high_slots', true),
				'midSlots' => \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_mid_slots', true),
				'lowSlots' => \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_low_slots', true),
				'rigSlots' => \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_rig_slots', true),
				'subSystems' => \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_subsystems', true),
				'drones' => \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_drones', true),
				'charges' => \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_charges', true),
			)));
			?>
		</div>
	</section>
</article><!-- /.post-->
