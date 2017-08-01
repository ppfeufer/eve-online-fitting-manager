/* global fittingManagerL10n, Clipboard, eftData */

jQuery(document).ready(function($) {
	/**
	 * Remove copy buttons if the browser doesn't supprt it
	 */
	if(!Clipboard.isSupported()) {
		$('.fitting-copy-to-clipboard').remove();
	} // END if(!Clipboard.isSupported())

	/**
	 * Show message when copy action was successfull
	 *
	 * @param {string} message
	 * @param {string} element
	 * @returns {undefined}
	 */
	function showSuccess(message, element) {
		$('<div class="alert alert-success alert-dismissable alert-copy-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + message + '</div>').insertAfter(element);

		/**
		 * close after 5 seconds
		 */
		$('.alert-copy-success').fadeTo(2000, 500).slideUp(500, function(){
			$(this).slideUp(500, function() {
				$(this).remove();
			});
		});

		return;
	} // END function showSuccess(message, element)

	/**
	 * Show message when copy action was not successfull
	 *
	 * @param {string} message
	 * @param {string} element
	 * @returns {undefined}
	 */
	function showError(message, element) {
		$('<div class="alert alert-danger alert-dismissable alert-copy-error"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + message + '</div>').insertAfter(element);

		return;
	} // END function showError(message, element)

	/**
	 * Copy EFT data to clipboard
	 */
	$('.btn-copy-eft-to-clipboard').on('click', function() {
		/**
		 * Copy EFT fitting to clipboard
		 *
		 * @type Clipboard
		 */
		var clipboardEftData = new Clipboard('.btn-copy-eft-to-clipboard');

		/**
		 * Copy success
		 *
		 * @param {type} e
		 */
		clipboardEftData.on('success', function(e) {
			showSuccess(fittingManagerL10n.copyToClipboard.eft.text.success, '.copy-eft-to-clipboard');

			e.clearSelection();
			clipboardEftData.destroy();
		});

		/**
		 * Copy error
		 */
		clipboardEftData.on('error', function() {
			showError(fittingManagerL10n.copyToClipboard.eft.text.error, '.copy-eft-to-clipboard');

			clipboardEftData.destroy();
		});
	});

	/**
	 * Copy permalink to clipboard
	 */
	$('.btn-copy-permalink-to-clipboard').on('click', function() {
		/**
		 * Copy EFT fitting to clipboard
		 *
		 * @type Clipboard
		 */
		var clipboardPermalinkData = new Clipboard('.btn-copy-permalink-to-clipboard');

		/**
		 * Copy success
		 *
		 * @param {type} e
		 */
		clipboardPermalinkData.on('success', function(e) {
			showSuccess(fittingManagerL10n.copyToClipboard.permalink.text.success, '.copy-permalink-to-clipboard');

			e.clearSelection();
			clipboardPermalinkData.destroy();
		});

		/**
		 * Copy error
		 */
		clipboardPermalinkData.on('error', function() {
			showError(fittingManagerL10n.copyToClipboard.permalink.text.error, '.copy-permalink-to-clipboard');

			clipboardPermalinkData.destroy();
		});
	});

	/**
	 * Ajax Call EVE Market Data
	 */
	var getEveFittingMarketData = {
		ajaxCall: function() {
			$.ajax({
				type: 'post',
				url: fittingManagerL10n.ajax.url,
				data: 'action=get-eve-fitting-market-data&nonce=' + fittingManagerL10n.ajax.eveFittingMarketData.nonce + '&eftData=' + eftData,
				dataType: 'json',
				success: function(result) {
					if(result !== null) {
						$('.eve-market-ship-buy').html(result.ship.jitaBuyPrice);
						$('.eve-market-fitting-buy').html(result.fitting.jitaBuyPrice);
						$('.eve-market-total-buy').html(result.total.jitaBuyPrice);

						$('.eve-market-ship-sell').html(result.ship.jitaSellPrice);
						$('.eve-market-fitting-sell').html(result.fitting.jitaSellPrice);
						$('.eve-market-total-sell').html(result.total.jitaSellPrice);
					}
				},
				error: function(jqXHR, textStatus, errorThrow) {
					console.log('Ajax request - ' + textStatus + ': ' + errorThrow);
				}
			});
		}
	};

	/**
	 * Only call the market data ajax when the table is found in template
	 */
	if($('.fitting-market-price').length) {
		getEveFittingMarketData.ajaxCall();
	} // END if($('.fitting-market-price').length)
});
