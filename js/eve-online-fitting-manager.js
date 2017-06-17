/* global showTooltip */

jQuery(document).ready(function($) {
	if(showTooltip !== 'undefined' && showTooltip === true) {
		$(function () {
			$('[data-toggle="tooltip"]').tooltip();
		})
	}
});
