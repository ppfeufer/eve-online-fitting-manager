<?php defined('ABSPATH') or die(); ?>

<article id="post-<?php \the_ID(); ?>" <?php \post_class('clearfix content-single content-fitting'); ?>>
	<section class="post-content">
		<div class="entry-content">
			<a class="fitting-list-item" href="<?php echo \get_the_permalink(); ?>">
				<h3 class="doctrine-shipfitting-header">
					<span class="doctrine-shipfitting-header-wrapper">
						<span class="doctrine-shipfitting-header-ship-image">
							<img src="<?php echo \WordPress\Plugin\EveOnlineFittingManager\Helper\FittingHelper::getShipImageById(\get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_ship_ID', true), 64); ?>">
						</span>
						<span class="doctrine-shipfitting-header-fitting-name">
							<?php echo \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_ship_type', true); ?><br>
							<?php echo \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_name', true); ?>
						</span>
					</span>
				</h3>
			</a>
		</div>
	</section>
</article><!-- /.post-->
