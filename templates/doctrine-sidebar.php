
<div class="col-lg-3 col-md-3 col-sm-3 col-3 sidebar-wrapper">
	<?php
	/**
	 * Filter the Navigation by doctrines
	 */
	$countDoctrineShips = \get_terms(array('taxonomy' => 'fitting-doctrines', 'fields' => 'count'));
	if($countDoctrineShips > 0) {
		?>
		<aside>
			<div class="widget">
				<h4 class="widget-title"><?php echo \__('Search Doctrines', 'eve-online-fitting-manager'); ?></h4>
				<div class="fitting-sidebar-search">
					<form action="/<?php echo WordPress\Plugin\EveOnlineFittingManager\Libs\PostType::getPosttypeSlug('fittings'); ?>/" method="GET" id="fitting_search" role="search">
						<div class="input-group">
							<label class="sr-only" for="fitting_search"><?php echo \__('Search', 'eve-online-fitting-manager') ?></label>
							<input type="text" class="form-control" id="fitting_search" name="fitting_search" placeholder="<?php echo \__('Search Ship Type', 'eve-online-fitting-manager') ?>" value="<?php echo WordPress\Plugin\EveOnlineFittingManager\Helper\FittingHelper::getFittingSearchQuery(true); ?>">
							<div class="input-group-btn">
								<button type="submit" class="btn btn-default">
									<span class="glyphicon glyphicon-search"></span>
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</aside>

		<aside>
			<div class="widget">
				<h4 class="widget-title"><?php echo \__('Doctrines', 'eve-online-fitting-manager'); ?></h4>
				<?php echo \WordPress\Plugin\EveOnlineFittingManager\Helper\FittingHelper::getSidebarMenu('fitting-doctrines'); ?>
			</div>
		</aside>
		<?php
	} // END if($countDoctrineShips > 0)

	/**
	 * Filter the Navigation by ship types
	 */
	$countShipTypes = \get_terms(array('taxonomy' => 'fitting-ships', 'fields' => 'count'));
	if($countShipTypes > 0) {
		?>
		<aside>
			<div class="widget">
				<h4 class="widget-title"><?php echo \__('Ship Types', 'eve-online-fitting-manager'); ?></h4>
				<?php echo \WordPress\Plugin\EveOnlineFittingManager\Helper\FittingHelper::getSidebarMenu('fitting-ships'); ?>
			</div>
		</aside>
		<?php
	} // END if($countShipTypes > 0)
	?>

	<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(function() {
			var doctrineSlug = $('.container.main').data('doctrine');
			var currentDoctrine = $('.sidebar-doctrine-list').find('[data-doctrine="' + doctrineSlug + '"]');

			currentDoctrine.addClass('doctrine-current');
			currentDoctrine.parent().parent().addClass('doctrine-active');
			currentDoctrine.parent().parent().parent().parent().addClass('doctrine-active');
		});
	});
	</script>
</div>
