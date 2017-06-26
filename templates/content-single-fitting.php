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

<article id="post-<?php \the_ID(); ?>" <?php \post_class('clearfix content-single content-single-fitting'); ?>>
	<section class="post-content">
		<div class="entry-content">
			<?php
			$fittingDescription = \get_the_content();
			if(!empty(\trim($fittingDescription))) {
				?>
				<div class="ship-fitting-description">
					<h3><?php echo \__('Fitting Description and Information', 'eve-online-fitting-manager'); ?></h3>

					<?php echo \wpautop($fittingDescription); ?>
				</div>
				<?php
			} // END if(!empty(\trim($fittingDescription)))
			?>

			<div class="row">
				<div class="col-lg-6 col-xl-6 ship-fitting-visualization">
					<!--<h3><?php echo \__('Fitting Visualization', 'eve-online-fitting-manager'); ?></h3>-->
					<?php
					\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting/fitting-ring');

					$usedInDoctrines = \WordPress\Plugin\EveOnlineFittingManager\Helper\FittingHelper::getShipUsedInDoctrine();
					if(!empty($usedInDoctrines) && !\is_wp_error($usedInDoctrines)) {
						?>
						<div class="fitting-used-in">
							<p>
								<?php echo \__('This fitting is used in the following doctrines:', 'eve-online-fitting-manager'); ?>
							</p>
							<?php
							echo '<ul class="fitting-used-in-doctrines">';
							foreach($usedInDoctrines as $doctrine) {
								echo '<li>» <a href="' . \get_term_link($doctrine) . '">' . $doctrine->name . '</a></li>';
							} // END foreach($usedInDoctrines as $doctrine)
							echo '</ul>';
							?>
						</div>
						<?php
					} // END if(!empty($usedInDoctrines) && !\is_wp_error($usedInDoctrines))
					?>
				</div>

				<div class="col-lg-6 col-xl-6 ship-fitting-eft-import">
					<h3><?php echo \__('EFT Import', 'eve-online-fitting-manager'); ?></h3>
					<?php
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
			</div>
		</div>
	</section>
</article><!-- /.post-->
