<?php
defined('ABSPATH') or die();

\get_header();
?>

<div class="container main">
	<div class="main-content clearfix">
		<div class="col-lg-12 col-md-12 col-sm-12 col-12 content-wrapper">
			<div class="content content-inner content-single content-fitting">
				<?php
				if(\have_posts()) {
					while(\have_posts()) {
						\the_post();
						\WordPress\Plugin\EveOnlineFittingManager\Helper\TemplateHelper::getTemplate('content-fitting');
					} // END while(have_posts())
				} // END if(have_posts())
				?>
			</div> <!-- /.content -->
		</div> <!-- /.col-lg-9 /.col-md-9 /.col-sm-9 /.col-9 -->
	</div> <!-- /.row -->
</div> <!-- /.container -->

<?php \get_footer(); ?>
