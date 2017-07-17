<?php
defined('ABSPATH') or die();

$pluginOptions = \WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginSettings();
?>

<article id="post-<?php \the_ID(); ?>" <?php \post_class('clearfix content-single template-content-fitting'); ?>>
	<section class="post-content">
		<div class="entry-content">
			<?php
			if(!empty($pluginOptions['template-settings']['show-ship-images-in-loop'])) {
				?>
				<a href="<?php \the_permalink(); ?>" title="<?php \the_title_attribute('echo=0'); ?>">
					<figure class="post-loop-thumbnail">
						<?php
						if(\has_post_thumbnail()) {
							if(\function_exists('\fly_get_attachment_image')) {
								echo \fly_get_attachment_image(\get_post_thumbnail_id(), 'post-loop-thumbnail');
							} else {
								\the_post_thumbnail('post-loop-thumbnail');
							} // END if(\function_exists('\fly_get_attachment_image'))
						} else {
							// Load our dummy ...
							?>
							<img width="705" height="395" src="<?php echo \WordPress\Plugin\EveOnlineFittingManager\Helper\PluginHelper::getPluginUri('images/fitting-dummy.jpg'); ?>" class="attachment-post-loop-thumbnail img-responsive" alt="<?php echo \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_ship_type', true); ?>">
							<?php
						} // END if(\has_post_thumbnail())
						?>
					</figure>
				</a>

				<header class="entry-header">
					<h2 class="entry-title">
						<a class="doctrine-link-item" href="<?php \the_permalink(); ?>" title="<?php \printf(\esc_attr__('Permalink to %s', 'eve-online'), \the_title_attribute('echo=0')); ?>" rel="bookmark">
							<?php echo \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_ship_type', true); ?><br>
							<?php echo \get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_fitting_name', true); ?>
						</a>
					</h2>
					<aside class="entry-details">
						<p class="meta">
							<?php
							\edit_post_link(__('Edit', 'eve-online'));
							?>
						</p>
					</aside><!--end .entry-details -->
				</header><!--end .entry-header -->
				<?php
			} else {
				?>
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
				<?php
			} // END if(!empty($pluginOptions['template-settings']['show-ship-images-in-loop']))
			?>
		</div>
	</section>
</article><!-- /.post-->
