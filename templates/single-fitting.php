<?php
defined('ABSPATH') or die();

\get_header();
?>

<div class="container main template-single-fitting">
	<div class="main-content clearfix">
		<div class="col-lg-9 col-md-9 col-sm-9 col-9 content-wrapper">
			<div class="content content-inner content-single content-fitting">
				<?php
				if(\have_posts()) {
					while(\have_posts()) {
						\the_post();
						\WordPress\Plugin\EveOnlineFittingManager\Libs\Helper\TemplateHelper::getTemplate('content-single-fitting');
					} // END while(have_posts())
				} // END if(have_posts())
				?>
			</div> <!-- /.content -->
		</div> <!-- /.col-lg-9 /.col-md-9 /.col-sm-9 /.col-9 -->
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
	</div> <!-- /.row -->
</div> <!-- /.container -->

<?php \get_footer(); ?>
