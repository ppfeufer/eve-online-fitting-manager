<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * Template Name: WordPress Plugins
 *
 * @since Talos 1.0
 *
 * @package WordPress
 * @subpackage Talos Theme
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
							<?php echo \the_content(); ?>
						</article>
					</div> <!-- /.content -->
				</div> <!-- /.col -->

				<?php
				\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('doctrine-sidebar');
				?>
			</div> <!--/.row -->
			<?php
		}
	} // END if(have_posts())
	?>
</div><!-- /.container -->

<?php get_footer(); ?>
