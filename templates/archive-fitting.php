<?php
defined('ABSPATH') or die();

\get_header();
$taxonomy = 'fitting-doctrines';
$doctrineData = \get_queried_object();

$subDoctrines = \get_terms([
	'taxonomy' => $taxonomy,
	'orderby' => 'name',
	'order' => 'ASC',
	'child_of' => $doctrineData->term_id
]);
?>

<div class="container main template-archive-fitting" data-doctrine="<?php echo $doctrineData->slug; ?>">
	<div class="main-content clearfix">
		<div class="<?php echo \WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\PluginHelper::getMainContentColClasses(); ?>">
			<div class="content content-archive doctrine-list">
				<header class="page-title">
					<h2>
						<?php echo \__('Doctrine:', 'eve-online-fitting-manager') . ' ' . $doctrineData->name; ?>
					</h2>
					<?php
					// Show an optional category description
					if(!empty($doctrineData->description)) {
						echo \apply_filters('category_archive_meta', '<div class="category-archive-meta">' . $doctrineData->description . '</div>');
					} // END if(!empty($doctrine->description))
					?>
				</header>
				<?php

				$argsMainDoctrine = [
					'post_type' => 'fitting',
					'tax_query' => [
						[
							'taxonomy' => $taxonomy,
							'field' => 'id',
							'terms' => $doctrineData->term_id,
							'include_children' => false
						]
					]
				];
				$resultMainDoctrine = new \WP_Query($argsMainDoctrine);

				// loop through the main doctrine
				if($resultMainDoctrine->have_posts()) {
					if(\get_post_type() === 'fitting') {
						$uniqueID = \uniqid();

						echo '<header class="entry-header header-doctrine"><h2 class="entry-title header-subdoctrine">' . \__('Main Line Doctrine Ships', 'eve-online-fitting-manager') . '</h2></header>';
						echo '<div class="gallery-row row">';
						echo '<ul class="bootstrap-post-loop-fittings bootstrap-post-loop-fittings-' . $uniqueID . ' clearfix">';
					} // END if(\get_post_type() === 'fitting')

					while($resultMainDoctrine->have_posts()) {
						$resultMainDoctrine->the_post();

						if(\get_post_type() === 'fitting') {
							echo '<li>';
						} // END if(\get_post_type() === 'fitting')

						\WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\TemplateHelper::getTemplate('content-fitting');

						if(\get_post_type() === 'fitting') {
							echo '</li>';
						} // END if(\get_post_type() === 'fitting')
					} // END while (have_posts())
					\wp_reset_postdata();

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
				} // if($resultMainDoctrine->have_posts())

				// Loop throgh the sub doctrines ...
				if(\count($subDoctrines) > 0) {
					foreach($subDoctrines as $subDoctrine) {
						$argsSubDoctrine = [
							'post_type' => 'fitting',
							'tax_query' => [
								[
									'taxonomy' => $taxonomy,
									'field' => 'id',
									'terms' => $subDoctrine->term_id,
									'include_children' => false
								]
							]
						];
						$resultSubDoctrine = new \WP_Query($argsSubDoctrine);

						if($resultSubDoctrine->have_posts()) {
							if(\get_post_type() === 'fitting') {
								$uniqueID = \uniqid();

								echo '<header class="entry-header header-doctrine"><h2 class="entry-title header-subdoctrine">' . $subDoctrine->name . '</h2></header>';
								echo '<div class="gallery-row row">';
								echo '<ul class="bootstrap-post-loop-fittings bootstrap-post-loop-fittings-' . $uniqueID . ' clearfix">';
							} // END if(\get_post_type() === 'fitting')

							while($resultSubDoctrine->have_posts()) {
								$resultSubDoctrine->the_post();

								if(\get_post_type() === 'fitting') {
									echo '<li>';
								} // END if(\get_post_type() === 'fitting')

								\WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\TemplateHelper::getTemplate('content-fitting');

								if(\get_post_type() === 'fitting') {
									echo '</li>';
								} // END if(\get_post_type() === 'fitting')
							} // END while (have_posts())
							\wp_reset_postdata();

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
						} // if($resultSubDoctrine->have_posts())
					}
				}
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
