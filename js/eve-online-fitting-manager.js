/* global fittingManagerL10n, Clipboard, eftData */

jQuery(document).ready(function($) {
	/**
	 * Remove copy buttons if the browser doesn't supprt it
	 */
	if(!Clipboard.isSupported()) {
		$('.fitting-copy-to-clipboard').remove();
	} // END if(!Clipboard.isSupported())

	function closeCopyMessageElement(element) {
		/**
		 * close after 5 seconds
		 */
		$(element).fadeTo(2000, 500).slideUp(500, function(){
			$(this).slideUp(500, function() {
				$(this).remove();
			});
		});
	}

	/**
	 * Show message when copy action was successfull
	 *
	 * @param {string} message
	 * @param {string} element
	 * @returns {undefined}
	 */
	function showSuccess(message, element) {
		$(element).html('<div class="alert alert-success alert-dismissable alert-copy-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + message + '</div>');

		closeCopyMessageElement('.alert-copy-success');

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
		$(element).html('<div class="alert alert-danger alert-dismissable alert-copy-error"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + message + '</div>');

		closeCopyMessageElement('.alert-copy-error');

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
			showSuccess(fittingManagerL10n.copyToClipboard.eft.text.success, '.fitting-copy-result');

			e.clearSelection();
			clipboardEftData.destroy();
		});

		/**
		 * Copy error
		 */
		clipboardEftData.on('error', function() {
			showError(fittingManagerL10n.copyToClipboard.eft.text.error, '.fitting-copy-result');

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
			showSuccess(fittingManagerL10n.copyToClipboard.permalink.text.success, '.fitting-copy-result');

			e.clearSelection();
			clipboardPermalinkData.destroy();
		});

		/**
		 * Copy error
		 */
		clipboardPermalinkData.on('error', function() {
			showError(fittingManagerL10n.copyToClipboard.permalink.text.error, '.fitting-copy-result');

			clipboardPermalinkData.destroy();
		});
	});

	/**
	 * Market Data Ajax Update
	 */
	if($('.fitting-market-price').length) {
		/**
		 * Ajax Call EVE Market Data
		 */
		var getEveFittingMarketData = {
			ajaxCall: function() {
				$.ajax({
					type: 'post',
					url: fittingManagerL10n.ajax.url,
					data: 'action=get-eve-fitting-market-data&eftData=' + eftData,
					dataType: 'json',
					success: function(result) {
						if(result !== null) {
							$('.table-fitting-marketdata .eve-market-ship-buy').html(result.ship.jitaBuyPrice);
							$('.table-fitting-marketdata .eve-market-fitting-buy').html(result.fitting.jitaBuyPrice);
							$('.table-fitting-marketdata .eve-market-total-buy').html(result.total.jitaBuyPrice);

							$('.table-fitting-marketdata .eve-market-ship-sell').html(result.ship.jitaSellPrice);
							$('.table-fitting-marketdata .eve-market-fitting-sell').html(result.fitting.jitaSellPrice);
							$('.table-fitting-marketdata .eve-market-total-sell').html(result.total.jitaSellPrice);
						}
					},
					error: function(jqXHR, textStatus, errorThrow) {
						console.log('Ajax request - ' + textStatus + ': ' + errorThrow);
					}
				});
			}
		};

		var cSpeed = 5;
		var cWidth = 127;
		var cHeight = 19;
		var cTotalFrames = 20;
		var cFrameWidth = 127;
		var cImageSrc = fittingManagerL10n.ajax.loaderImage;

		var cImageTimeout = false;
		var cIndex = 0;
		var cXpos = 0;
		var cPreloaderTimeout = false;
		var SECONDS_BETWEEN_FRAMES = 0;

		/**
		 * Start animation
		 *
		 * @returns {undefined}
		 */
		var startAnimation = function() {
			$('.table-fitting-marketdata .loaderImage').css('display', 'block');
			$('.table-fitting-marketdata .loaderImage').css('backgroundImage', 'url(' + cImageSrc + ')');
			$('.table-fitting-marketdata .loaderImage').css('width', cWidth + 'px');
			$('.table-fitting-marketdata .loaderImage').css('height', cHeight + 'px');

			var FPS = Math.round(100 / cSpeed);
			SECONDS_BETWEEN_FRAMES = 1 / FPS;

			cPreloaderTimeout = setTimeout(continueAnimation, SECONDS_BETWEEN_FRAMES / 1000);
		};

		/**
		 * Continue animation
		 *
		 * @returns {undefined}
		 */
		var continueAnimation = function() {
			cXpos += cFrameWidth;

			/**
			 * increase the index so we know which frame
			 * of our animation we are currently on
			 */
			cIndex += 1;

			/**
			 * if our cIndex is higher than our total number of frames,
			 * we're at the end and should restart
			 */
			if(cIndex >= cTotalFrames) {
				cXpos = 0;
				cIndex = 0;
			}

			if($('.table-fitting-marketdata .loaderImage')) {
				$('.table-fitting-marketdata .loaderImage').css('backgroundPosition', (-cXpos) + 'px 0');
			}

			cPreloaderTimeout = setTimeout(continueAnimation, SECONDS_BETWEEN_FRAMES * 1000);
		};

		/**
		 * stops animation
		 *
		 * @returns {undefined}
		 */
		var stopAnimation = function() {
			clearTimeout(cPreloaderTimeout);
			cPreloaderTimeout = false;
		};

		/**
		 * Imageloader
		 *
		 * @param {type} s
		 * @param {type} fun
		 * @returns {undefined}
		 */
		var imageLoader = function(s, fun) {
			clearTimeout(cImageTimeout);
			cImageTimeout = 0;

			var genImage = new Image();
			genImage.onload = function() {
				cImageTimeout = setTimeout(fun, 0);
			};
			genImage.onerror = new Function('alert(\'Could not load the image\')');
			genImage.src = s;
		};

		/**
		 * Start the animation
		 */
		imageLoader(cImageSrc, startAnimation);

		/**
		 * Call the ajax to get the market data
		 */
		getEveFittingMarketData.ajaxCall();
	}
});
