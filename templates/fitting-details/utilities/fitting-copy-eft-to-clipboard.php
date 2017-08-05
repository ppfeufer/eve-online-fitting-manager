<?php
$col = ' col-xl-4';
if($isUpwellStructure === true) {
	$col = ' col-xl-6';
} // END if($isUpwellStructure === true)
?>


<div class="fitting-copy-to-clipboard copy-eft-to-clipboard<?php echo $col; ?>">
	<ul class="nav nav-pills nav-stacked">
		<li role="presentation">
			<span type="button" class="btn btn-default btn-copy-eft-to-clipboard" data-clipboard-action="copy" data-clipboard-target=".hidden-eft-for-copy"><?php echo \__('Copy EFT Data', 'eve-online-fitting-manager') ; ?></span>
		</li>
	</ul>
	<textarea class="hidden-eft-for-copy" style="width: 0; height: 0; border: none; position: absolute; left: -9999px; top:  -9999px;"><?php echo $eftFitting; ?></textarea>
</div>
