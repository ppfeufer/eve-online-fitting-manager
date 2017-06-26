
<div class="col-lg-3 col-md-3 col-sm-3 col-3 sidebar-wrapper">
	<h4 class="widget-title"><?php echo \__('Doctrines', 'eve-online-fitting-manager'); ?></h4>

	<div class="fitting-sidebar-search">
		<form action="/<?php echo WordPress\Plugin\EveOnlineFittingManager\Libs\PostType::getPosttypeSlug('fittings'); ?>/" method="GET" id="fitting_search" role="search">
			<div class="input-group">
				<label class="sr-only" for="fitting_search"><?php echo \__('Search', 'eve-online-fitting-manager') ?></label>
				<input type="text" class="form-control" id="fitting_search" name="fitting_search" placeholder="<?php echo \__('Search Ship Type', 'eve-online-fitting-manager') ?>" value="<?php echo WordPress\Plugin\EveOnlineFittingManager\Helper\FittingHelper::getFittingSearchQuery(true); ?>">
				<!--<input type="hidden" name="post_type" value="fitting" />-->
				<div class="input-group-btn">
					<button type="submit" class="btn btn-default">
						<span class="glyphicon glyphicon-search"></span>
					</button>
				</div>
			</div>
		</form>
	</div>

	<?php
	echo \WordPress\Plugin\EveOnlineFittingManager\Helper\FittingHelper::getSidebarDoctrineMenu();
	?>
</div>
