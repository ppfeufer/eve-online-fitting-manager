/* global showTooltip */

jQuery(document).ready(function($) {
	if(showTooltip === true) {
		$(function () {
			$('[data-toggle="tooltip"]').tooltip();
		})
	}
});
