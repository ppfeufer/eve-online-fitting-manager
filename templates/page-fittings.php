<?php
/**
 * Template Name: Fittings
 */
?>
<?php get_header(); ?>

<div class="container main">
	<?php
	if(\have_posts()) {
		while(\have_posts()) {
			\the_post();
			?>
			<!--<div class="row main-content">-->
			<div class="main-content clearfix">
				<div class="col-lg-9 col-md-9 col-sm-9 col-9 content-wrapper">
					<div class="content content-inner content-full-width content-page doctrine-fittings">
						<header>
							<?php
							if(\is_front_page()) {
								?>
								<h1><?php echo \get_bloginfo('name'); ?></h1>
								<?php
							} else {
								?>
								<h1><?php \the_title(); ?></h1>
								<?php
							} // END if(\is_front_page())
							?>
						</header>
						<article class="post clearfix" id="post-<?php \the_ID(); ?>">
							<?php
							if(!empty(\get_query_var('fitting_search'))) {
								$query = WordPress\Plugin\EveOnlineFittingManager\Helper\FittingHelper::searchFittings();

								if($query->have_posts()) {
									$uniqueID = \uniqid();

									echo '<div class="gallery-row row">';
									echo '<ul class="bootstrap-post-loop-fittings bootstrap-post-loop-fittings-' . $uniqueID . ' clearfix">';

									while($query->have_posts()) {
										$query->the_post();

										echo '<li>';
										\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('content-fitting');
										echo '</li>';
									}

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
								}
							} else {
								echo \the_content();
							}
							?>
						</article>
					</div> <!-- /.content -->
				</div> <!-- /.col -->

				<?php
				\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('doctrine-sidebar');
				?>
			</div> <!--/.row -->
			<?php
		} // END while(\have_posts())
	} // END if(have_posts())
	?>
</div><!-- /.container -->

<?php get_footer(); ?>
