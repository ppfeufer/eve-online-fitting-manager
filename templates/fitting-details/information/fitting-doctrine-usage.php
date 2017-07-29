<?php
$usedInDoctrines = \WordPress\Plugin\EveOnlineFittingManager\Helper\FittingHelper::getShipUsedInDoctrine();
?>

<div class="fitting-used-in">
	<h4>
		<?php echo \__('Doctrines using this fitting', 'eve-online-fitting-manager'); ?>
	</h4>
	<?php
	$fittingUsedInHtml = '<div class="bs-callout bs-callout-info">';
	$fittingUsedInHtml .= '<p class="small">';
	$fittingUsedInHtml .= \__('This fitting is currently not used in any doctrine.', 'eve-online-fitting-manager');
	$fittingUsedInHtml .= '</p>';
	$fittingUsedInHtml .= '</div>';

	if(!empty($usedInDoctrines) && !\is_wp_error($usedInDoctrines)) {
		$fittingUsedInHtml = '<ul class="fitting-used-in-doctrines">';
		foreach($usedInDoctrines as $doctrine) {
			$fittingUsedInHtml .= '<li>» <a href="' . \get_term_link($doctrine) . '">' . $doctrine->name . '</a></li>';
		} // END foreach($usedInDoctrines as $doctrine)
		$fittingUsedInHtml .= '</ul>';
	} // END if(!empty($usedInDoctrines) && !\is_wp_error($usedInDoctrines))

	echo $fittingUsedInHtml;
	?>
</div>