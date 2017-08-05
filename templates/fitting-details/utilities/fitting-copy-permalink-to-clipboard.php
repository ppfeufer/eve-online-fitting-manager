<?php
$col = ' col-xl-4';
if($isUpwellStructure === true) {
	$col = ' col-xl-6';
} // END if($isUpwellStructure === true)
?>

<div class="fitting-copy-to-clipboard copy-permalink-to-clipboard<?php echo $col; ?>">
	<ul class="nav nav-pills nav-stacked">
		<li role="presentation">
			<span type="button" class="btn btn-default btn-copy-permalink-to-clipboard" data-clipboard-action="copy" data-clipboard-text="<?php echo \get_the_permalink(); ?>"><?php echo \__('Copy Permalink', 'eve-online-fitting-manager') ; ?></span>
		</li>
	</ul>
</div>
