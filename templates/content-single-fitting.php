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

<article id="post-<?php \the_ID(); ?>" <?php \post_class('clearfix content-single template-content-single-fitting'); ?>>
	<section class="post-content">
		<div class="entry-content">
			<?php
			$isConcept = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_is_concept', true);
			$isIdea = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_is_idea', true);
			$isUnderDiscussion = \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_is_under_discussion', true);

			if($isConcept === '1' || $isIdea === '1' || $isUnderDiscussion === '1') {
				?>
				<div class="bs-callout bs-callout-warning">
					<p class="small">
						<?php
						echo \__('This fitting is marked as:', 'eve-online-fitting-manager');

						if($isConcept === '1') {
							echo '<br>» ' . \__('Conceptual Fitting', 'eve-online-fitting-manager');
						}

						if($isIdea === '1') {
							echo '<br>» ' . \__('Fitting Idea', 'eve-online-fitting-manager');
						}

						if($isUnderDiscussion === '1') {
							echo '<br>» ' . \__('Under Discussion', 'eve-online-fitting-manager');
						}
						?>
					</p>
					<p class="small">
						<?php echo \__('This means, this fitting might not be a part of any official doctrine and/or is still under discussion.', 'eve-online-fitting-manager'); ?>
					</p>
				</div>
				<?php
			}

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
				<div class="col-lg-7 col-xl-6 ship-fitting-visualization">
					<?php
					\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('fitting/fitting-ring');

					$eftFitting = \WordPress\Plugin\EveOnlineFittingManager\Helper\EftHelper::getEftImportFromFitting(array(
						'shipID' => \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_ship_ID', true),
						'fittingType' => \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_name', true),
						'highSlots' => \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_high_slots', true),
						'midSlots' => \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_mid_slots', true),
						'lowSlots' => \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_low_slots', true),
						'rigSlots' => \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_rig_slots', true),
						'subSystems' => \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_subsystems', true),
						'serviceSlots' => \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_upwellservices', true),
						'drones' => \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_drones', true),
						'charges' => \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_charges', true),
					));
					$fittingArray = \WordPress\Plugin\EveOnlineFittingManager\Helper\EftHelper::getFittingArrayFromEftData($eftFitting);
					$marketPrices = \WordPress\Plugin\EveOnlineFittingManager\Helper\MarketdataHelper::getInstance()->getMarketPricesFromFittingArray($fittingArray);

					if($marketPrices !== false) {
						?>
						<div class="fitting-market-price price-jita">
							<h4>
								<?php echo \__('Estimated Prices', 'eve-online-fitting-manager'); ?>
							</h4>

							<div class="bs-callout bs-callout-info">
								<p class="small">
									<?php echo \__('These are only estimated market prices and can vary from ingame prices depending on the shiptype and market you are looking at. Especially capital ships that are not traded at the Jita market can be miles off.', ''); ?>
								</p>
							</div>

							<div class="col-sm-6 col-md-6 col-lg-12 col-xl-6">
								<div class="table-responsive">
									Jita Buy Order Price<br>
									<table class="table table-condensed table-fitting-marketdata">
										<tr>
											<th><?php echo \__('Ship:', 'eve-online-fitting-manager'); ?></th>
											<td><?php echo $marketPrices['ship']['jitaBuyPrice']; ?></td>
										</tr>
										<tr>
											<th><?php echo \__('Fitting:', 'eve-online-fitting-manager'); ?></th>
											<td><?php echo $marketPrices['fitting']['jitaBuyPrice']; ?></td>
										</tr>
										<tr>
											<th><?php echo \__('Total:', 'eve-online-fitting-manager'); ?></th>
											<td><?php echo $marketPrices['total']['jitaBuyPrice']; ?></td>
										</tr>
									</table>
								</div>
							</div>

							<div class="col-sm-6 col-md-6 col-lg-12 col-xl-6">
								<div class="table-responsive">
									Jita Sell Order Price<br>
									<table class="table table-condensed table-fitting-marketdata">
										<tr>
											<th><?php echo \__('Ship:', 'eve-online-fitting-manager'); ?></th>
											<td><?php echo $marketPrices['ship']['jitaSellPrice']; ?></td>
										</tr>
										<tr>
											<th><?php echo \__('Fitting:', 'eve-online-fitting-manager'); ?></th>
											<td><?php echo $marketPrices['fitting']['jitaSellPrice']; ?></td>
										</tr>
										<tr>
											<th><?php echo \__('Total:', 'eve-online-fitting-manager'); ?></th>
											<td><?php echo $marketPrices['total']['jitaSellPrice']; ?></td>
										</tr>
									</table>
								</div>
							</div>
						</div>
						<?php
					} // END if($marketPrices !== false)

					$usedInDoctrines = \WordPress\Plugin\EveOnlineFittingManager\Helper\FittingHelper::getShipUsedInDoctrine();
					?>
					<div class="fitting-used-in">
						<h4>
							<?php echo \__('Doctrines using this fitting', 'eve-online-fitting-manager'); ?>
						</h4>
						<?php
						$fittingUsedInHtml = '<div class="bs-callout bs-callout-info">';
						$fittingUsedInHtml .= '<p class="small">';
						$fittingUsedInHtml .= \__('This fitting is currently not used in any doctrine.', 'eve-online-fitting-manager');
						$fittingUsedInHtml .= '</p>';
						$fittingUsedInHtml .= '</div>';
						if(!empty($usedInDoctrines) && !\is_wp_error($usedInDoctrines)) {
							$fittingUsedInHtml = '<ul class="fitting-used-in-doctrines">';
							foreach($usedInDoctrines as $doctrine) {
								$fittingUsedInHtml .= '<li>» <a href="' . \get_term_link($doctrine) . '">' . $doctrine->name . '</a></li>';
							} // END foreach($usedInDoctrines as $doctrine)
							$fittingUsedInHtml .= '</ul>';
						} // END if(!empty($usedInDoctrines) && !\is_wp_error($usedInDoctrines))

						echo $fittingUsedInHtml;
						?>
					</div>
				</div>

				<div class="col-lg-5 col-xl-6 ship-fitting-eft-import">
					<h3><?php echo \__('EFT Import', 'eve-online-fitting-manager'); ?></h3>
					<?php
					echo \nl2br($eftFitting);
					?>
				</div>
			</div>
		</div>
	</section>
</article><!-- /.post-->
