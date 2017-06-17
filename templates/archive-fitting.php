<?php
defined('ABSPATH') or die();

\get_header();
?>

<div class="container main">
	<div class="main-content clearfix">
		<div class="col-lg-9 col-md-9 col-sm-9 col-9">
			<div class="content content-archive doctrine-list">
				<header class="page-title">
					<h2>
						<?php
						echo \__('Doctrine:', 'eve-online') . \single_cat_title(' ', false);
						?>
					</h2>
					<?php
					// Show an optional category description
					$category_description = \category_description();
					if($category_description) {
						echo \apply_filters('category_archive_meta', '<div class="category-archive-meta">' . $category_description . '</div>');
					} // END if($category_description)
					?>
				</header>
				<?php
				if(\have_posts()) {
					if(\get_post_type() === 'fitting') {
						$uniqueID = \uniqid();

						echo '<div class="gallery-row row">';
						echo '<ul class="bootstrap-post-loop-fittings bootstrap-post-loop-fittings-' . $uniqueID . ' clearfix">';
					} // END if(\get_post_type() === 'fitting')

					while(\have_posts()) {
						\the_post();

						if(\get_post_type() === 'fitting') {
							echo '<li>';
						} // END if(\get_post_type() === 'fitting')

//						\get_template_part('content', \get_post_format());
						\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('content-fitting');

						if(\get_post_type() === 'fitting') {
							echo '</li>';
						} // END if(\get_post_type() === 'fitting')
					} // END while (have_posts())

					if(\get_post_type() === 'fitting') {
						echo '</ul>';
						echo '</div>';

						echo '<script type="text/javascript">
								jQuery(document).ready(function() {
									jQuery("ul.bootstrap-post-loop-fittings-' . $uniqueID . '").bootstrapGallery({
										"classes" : "col-lg-4 col-md-6 col-sm-6 col-xs-12",
										"hasModal" : false
									});
								});
								</script>';
					} // END if(\get_post_type() === 'fitting')
				} // END if(have_posts())

//				if(\function_exists('wp_pagenavi')) {
//					\wp_pagenavi();
//				} else {
//					\WordPress\Themes\EveOnline\Helper\NavigationHelper::getContentNav('nav-below');
//				} // END if(\function_exists('wp_pagenavi'))
				?>
			</div> <!-- /.content -->
		</div> <!-- /.col -->

		<?php
		\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('doctrine-sidebar');
		?>

	</div> <!--/.row -->
</div><!-- container -->

<?php \get_footer(); ?>
