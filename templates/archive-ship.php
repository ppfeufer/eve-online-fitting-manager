<?php
defined('ABSPATH') or die();

\get_header();

$doctrineData = \get_queried_object();
?>

<div class="container main template-archive-ship" data-doctrine="<?php echo $doctrineData->slug; ?>">
	<div class="main-content clearfix">
		<div class="<?php echo \WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\PluginHelper::getMainContentColClasses(); ?>">
			<div class="content content-archive doctrine-list">
				<header class="page-title">
					<h2>
						<?php echo \__('Ship:', 'eve-online-fitting-manager') . ' ' . $doctrineData->name; ?>
					</h2>
					<?php
					// Show an optional category description
					if(!empty($doctrineData->description)) {
						echo \apply_filters('category_archive_meta', '<div class="category-archive-meta">' . $doctrineData->description . '</div>');
					} // END if(!empty($doctrine->description))
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

						\WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\TemplateHelper::getTemplate('content-fitting');

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
										"classes" : "' . \WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\PluginHelper::getLoopContentClasses() . '",
										"hasModal" : false
									});
								});
								</script>';
					} // END if(\get_post_type() === 'fitting')
				} else {
					echo \__('Sorry, but currently there are no fittings listed for this ship type. Maybe using the search function will help you out.', 'eve-online-fitting-manager');
				} // END if(have_posts())
				?>
			</div> <!-- /.content -->
		</div> <!-- /.col -->

		<?php
		if(\WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\PluginHelper::hasSidebar('sidebar-fitting-manager')) {
			?>
			<div class="col-lg-3 col-md-3 col-sm-3 col-3 sidebar-wrapper">
				<?php
				\WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\TemplateHelper::getTemplate('sidebar-fitting-manager');
				?>
			</div><!--/.col -->
			<?php
		} // END if(\WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\PluginHelper::hasSidebar('sidebar-fitting-manager'))
		?>
	</div> <!--/.row -->
</div><!-- container -->

<?php \get_footer(); ?>
