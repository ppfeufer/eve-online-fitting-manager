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

			$('<div class="alert alert-success alert-dismissable alert-copy-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>EFT data successfully copied</div>').insertAfter('.fitting-copy-to-clipboard');

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

			$('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Something went wrong. Nothing copied. Maybe your browser doesn\'t support this function.</div>').insertAfter('.fitting-copy-to-clipboard');

			clipboard.destroy();
		});
	});
});
