<div>
	<!-- Nav tabs -->
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active">
			<a href="#eft-fitting" aria-controls="eft-fitting" role="tab" data-toggle="tab"><h4>EFT Import</h4></a>
		</li>
		<?php
		$fittingDescription = \get_the_content();
		if(!empty(\trim($fittingDescription))) {
			?>
			<li role="presentation">
				<a href="#fitting-description" aria-controls="fitting-description" role="tab" data-toggle="tab"><h4>Fitting Information</h4></a>
			</li>
			<?php
		} // END if(!empty(\trim($fittingDescription)))
		?>
	</ul>

	<!-- Tab panes -->
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active ship-fitting-eft-import" id="eft-fitting">
			<p><?php echo \nl2br($eftFitting); ?></p>
		</div>
		<?php
		if(!empty(\trim($fittingDescription))) {
			?>
			<div role="tabpanel" class="tab-pane ship-fitting-description" id="fitting-description">
				<?php echo \wpautop($fittingDescription); ?>
			</div>
			<?php
		} // END if(!empty(\trim($fittingDescription)))
		?>
	</div>
</div>
