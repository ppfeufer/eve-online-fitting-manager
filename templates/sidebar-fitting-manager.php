<?php defined('ABSPATH') or die(); ?>

<section class="sidebar-fitting-manager">
	<?php
	if(\function_exists('\dynamic_sidebar')) {
		\dynamic_sidebar('sidebar-fitting-manager');
		?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$(function() {
				var doctrineSlug = $('.container.main').data('doctrine');
				var currentDoctrine = $('.sidebar-doctrine-list').find('[data-doctrine="' + doctrineSlug + '"]');

				currentDoctrine.addClass('doctrine-current');
				currentDoctrine.parent().parent().addClass('doctrine-active');
				currentDoctrine.parent().parent().parent().parent().addClass('doctrine-active');
			});
		});
		</script>
		<?php
	} // END if(function_exists('dynamic_sidebar'))
	?>
</section>
