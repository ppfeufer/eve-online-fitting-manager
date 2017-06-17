
<div class="col-lg-3 col-md-3 col-sm-3 col-3 sidebar-wrapper">
	<h4 class="widget-title"><?php echo \__('Doctrines', 'eve-online-fitting-manager'); ?></h4>
	<ul class="sidebar-doctrine-list">
		<?php
		echo \wp_list_categories(array(
			'taxonomy' => 'fitting-categories',
			'depth' => 999,
//			'hide_empty' => 0,
			'title_li' => '',
			'echo' => false,
			'show_count' => true
		));
		?>
	</ul>
</div>
