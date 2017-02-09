<?php
defined('ABSPATH') or die();

\get_header();
?>

<div class="container main">
	<div class="row main-content">
		<div class="col-lg-12 col-md-12 col-sm-12 col-12">
			<div class="content content-archive">
				<header class="page-title">
					<h1>
						<?php
							\_e('Doctrine Archives', 'eve-online');
						?>
					</h1>
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
					if(\get_post_type() === 'post') {
						$uniqueID = \uniqid();

						echo '<div class="gallery-row">';
						echo '<ul class="bootstrap-gallery bootstrap-post-loop-gallery bootstrap-post-loop-gallery-' . $uniqueID . ' clearfix">';
					} // END if(\get_post_type() === 'post')

					while(\have_posts()) {
						\the_post();

						if(\get_post_type() === 'post') {
							echo '<li>';
						}

						\get_template_part('content', \get_post_format());

						if(\get_post_type() === 'post') {
							echo '</li>';
						}
					} // END while (have_posts())

					if(\get_post_type() === 'post') {
						echo '</ul>';
						echo '</div>';

						echo '<script type="text/javascript">
								jQuery(document).ready(function() {
									jQuery("ul.bootstrap-post-loop-gallery-' . $uniqueID . '").bootstrapGallery({
										"classes" : "col-lg-3 col-md-4 col-sm-6 col-xs-12",
										"hasModal" : false
									});
								});
								</script>';
					} // END if(\get_post_type() === 'post')
				} // END if(have_posts())

//				if(\function_exists('wp_pagenavi')) {
//					\wp_pagenavi();
//				} else {
//					\WordPress\Themes\EveOnline\Helper\NavigationHelper::getContentNav('nav-below');
//				} // END if(\function_exists('wp_pagenavi'))
				?>
			</div> <!-- /.content -->
		</div> <!-- /.col -->
	</div> <!--/.row -->
</div><!-- container -->

<?php \get_footer(); ?>