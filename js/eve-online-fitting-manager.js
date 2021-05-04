/* global fittingManagerL10n, Clipboard, eftData */

jQuery(document).ready(function ($) {
    'use strict';

    /**
     * Remove copy buttons if the browser doesn't supprt it
     */
    if (!Clipboard.isSupported()) {
        $('.fitting-copy-to-clipboard').remove();
    } // END if(!Clipboard.isSupported())

    function closeCopyMessageElement(element) {
        /**
         * close after 5 seconds
         */
        $(element).fadeTo(2000, 500).slideUp(500, function () {
            $(this).slideUp(500, function () {
                $(this).remove();
            });
        });
    }

    /**
     * Show message when copy action was successfull
     *
     * @param {string} message
     * @param {string} element
     */
    function showSuccess(message, element) {
        $(element).html(
            '<div class="alert alert-success alert-dismissable alert-copy-success">' +
            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + message +
            '</div>'
        );

        closeCopyMessageElement('.alert-copy-success');
    } // END function showSuccess(message, element)

    /**
     * Show message when copy action was not successfull
     *
     * @param {string} message
     * @param {string} element
     */
    function showError(message, element) {
        $(element).html(
            '<div class="alert alert-danger alert-dismissable alert-copy-error">' +
            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + message +
            '</div>'
        );

        closeCopyMessageElement('.alert-copy-error');
    } // END function showError(message, element)

    /**
     * Copy EFT data to clipboard
     */
    $('.btn-copy-eft-to-clipboard').on('click', function () {
        /**
         * Copy EFT fitting to clipboard
         *
         * @type Clipboard
         */
        let clipboardEftData = new Clipboard('.btn-copy-eft-to-clipboard');

        /**
         * Copy success
         *
         * @param {type} e
         */
        clipboardEftData.on('success', function (e) {
            showSuccess(fittingManagerL10n.copyToClipboard.eft.text.success, '.fitting-copy-result');

            e.clearSelection();
            clipboardEftData.destroy();
        });

        /**
         * Copy error
         */
        clipboardEftData.on('error', function () {
            showError(fittingManagerL10n.copyToClipboard.eft.text.error, '.fitting-copy-result');

            clipboardEftData.destroy();
        });
    });

    /**
     * Copy permalink to clipboard
     */
    $('.btn-copy-permalink-to-clipboard').on('click', function () {
        /**
         * Copy EFT fitting to clipboard
         *
         * @type Clipboard
         */
        let clipboardPermalinkData = new Clipboard('.btn-copy-permalink-to-clipboard');

        /**
         * Copy success
         *
         * @param {type} e
         */
        clipboardPermalinkData.on('success', function (e) {
            showSuccess(fittingManagerL10n.copyToClipboard.permalink.text.success, '.fitting-copy-result');

            e.clearSelection();
            clipboardPermalinkData.destroy();
        });

        /**
         * Copy error
         */
        clipboardPermalinkData.on('error', function () {
            showError(fittingManagerL10n.copyToClipboard.permalink.text.error, '.fitting-copy-result');

            clipboardPermalinkData.destroy();
        });
    });

    /**
     * Market Data Ajax Update
     */
    if ($('.fitting-market-price').length) {
        /**
         * Ajax Call EVE Market Data
         */
        let getEveFittingMarketData = {
            ajaxCall: function () {
                $.ajax({
                    type: 'post',
                    url: fittingManagerL10n.ajax.url,
                    data: 'action=get-eve-fitting-market-data&eftData=' + eftData,
                    dataType: 'json',
                    success: function (result) {
                        if (result !== null) {
                            $('.table-fitting-marketdata .eve-market-ship-buy').html(result.ship.jitaBuyPrice);
                            $('.table-fitting-marketdata .eve-market-fitting-buy').html(result.fitting.jitaBuyPrice);
                            $('.table-fitting-marketdata .eve-market-total-buy').html(result.total.jitaBuyPrice);

                            $('.table-fitting-marketdata .eve-market-ship-sell').html(result.ship.jitaSellPrice);
                            $('.table-fitting-marketdata .eve-market-fitting-sell').html(result.fitting.jitaSellPrice);
                            $('.table-fitting-marketdata .eve-market-total-sell').html(result.total.jitaSellPrice);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrow) {
                        console.log('Ajax request - ' + textStatus + ': ' + errorThrow);
                    }
                });
            }
        };

        let cSpeed = 5;
        let cWidth = 127;
        let cHeight = 19;
        let cTotalFrames = 20;
        let cFrameWidth = 127;
        let cImageSrc = fittingManagerL10n.ajax.loaderImage;

        let cImageTimeout = false;
        let cIndex = 0;
        let cXpos = 0;
        let cPreloaderTimeout = false;
        let SECONDS_BETWEEN_FRAMES = 0;

        /**
         * Start animation
         *
         * @returns {undefined}
         */
        let startAnimation = function () {
            let loaderImageElement = $('.table-fitting-marketdata .loaderImage');
            loaderImageElement.css('display', 'block');
            loaderImageElement.css('backgroundImage', 'url(' + cImageSrc + ')');
            loaderImageElement.css('width', cWidth + 'px');
            loaderImageElement.css('height', cHeight + 'px');

            let FPS = Math.round(100 / cSpeed);
            SECONDS_BETWEEN_FRAMES = 1 / FPS;

            cPreloaderTimeout = setTimeout(continueAnimation, SECONDS_BETWEEN_FRAMES / 1000);
        };

        /**
         * Continue animation
         *
         * @returns {undefined}
         */
        let continueAnimation = function () {
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
            if (cIndex >= cTotalFrames) {
                cXpos = 0;
                cIndex = 0;
            }

            let loaderImageElement = $('.table-fitting-marketdata .loaderImage');
            if (loaderImageElement) {
                loaderImageElement.css('backgroundPosition', (-cXpos) + 'px 0');
            }

            cPreloaderTimeout = setTimeout(continueAnimation, SECONDS_BETWEEN_FRAMES * 1000);
        };

        /**
         * stops animation
         */
        let stopAnimation = function () {
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
        let imageLoader = function (s, fun) {
            clearTimeout(cImageTimeout);
            cImageTimeout = 0;

            let genImage = new Image();
            genImage.onload = function () {
                cImageTimeout = setTimeout(fun, 0);
            };
            genImage.onerror = console.log('Could not load the image');
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
