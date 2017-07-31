/* global fittingManagerL10n */

jQuery(document).ready(function($) {
	$('.btn-copy-to-clipboard').on('click', function() {
		/**
		 * Copy EFT fitting to clipboard
		 *
		 * @type Clipboard
		 */
		var clipboard = new Clipboard('.btn-copy-to-clipboard');

		/**
		 * Copy success
		 */
		clipboard.on('success', function(e) {
//			console.info('Action:', e.action);
//			console.info('Text:', e.text);
//			console.info('Trigger:', e.trigger);

			$('<div class="alert alert-success alert-dismissable alert-copy-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + fittingManagerL10n.copyToClipboard.text.success + '</div>').insertAfter('.fitting-copy-to-clipboard');

			// close after 5 seconds
			$('.alert-copy-success').fadeTo(2000, 500).slideUp(500, function(){
				$(this).slideUp(500, function() {
					$(this).remove();
				});
			});

			e.clearSelection();
			clipboard.destroy();
		});

		/**
		 * Copy error
		 */
		clipboard.on('error', function(e) {
//			console.error('Action:', e.action);
//			console.error('Trigger:', e.trigger);

			$('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + fittingManagerL10n.copyToClipboard.text.error + '</div>').insertAfter('.fitting-copy-to-clipboard');

			clipboard.destroy();
		});
	});
});
