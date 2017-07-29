<?php
if(\WordPress\Plugin\EveOnlineFittingManager\Helper\FittingHelper::isUpwellStructure($shipID) === false) {
	?>
	<div class="fitting-view-osmium-loadout">
		<ul class="nav nav-pills nav-stacked">
			<li role="presentation">
				<a href="https://o.smium.org/loadout/dna/<?php echo $fittingDna; ?>" type="button" class="btn btn-default" target="_blank"><?php echo \__('View Loadout @Osmium.org', 'eve-online-fitting-manager') ; ?></a>
			</li>
		</ul>
	</div>
	<?php
}
