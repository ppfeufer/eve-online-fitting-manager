<?php
if(\WordPress\Plugin\EveOnlineFittingManager\Helper\FittingHelper::isUpwellStructure($shipID) === false) {
	?>
	<div class="fitting-view-osmium-loadout col-xl-4">
		<ul class="nav nav-pills nav-stacked">
			<li role="presentation">
				<a href="https://o.smium.org/loadout/dna/<?php echo $fittingDna; ?>" type="button" class="btn btn-default" target="_blank"><?php echo \__('View @Osmium.org', 'eve-online-fitting-manager') ; ?></a>
			</li>
		</ul>
	</div>
	<?php
}
