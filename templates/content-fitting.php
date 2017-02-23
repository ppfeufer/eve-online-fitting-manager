<?php defined('ABSPATH') or die(); ?>

<article id="post-<?php \the_ID(); ?>" <?php \post_class('clearfix content-single content-fitting'); ?>>
	<header class="entry-header">
		<h1 class="entry-title">
			<?php \the_title(); ?>
		</h1>
		<aside class="entry-details">
			<p class="meta">
				<?php \edit_post_link(\__('Edit', 'eve-online-fitting-manager')); ?>
			</p>
		</aside><!--end .entry-details -->
	</header><!--end .entry-header -->

	<section class="post-content">
		<div class="entry-content">
			<?php
//			echo \the_content();
			echo \nl2br(\get_post_meta(\get_the_ID(), 'eve-online-fitting-manager_eft-import', true));
			?>
		</div>
	</section>
</article><!-- /.post-->