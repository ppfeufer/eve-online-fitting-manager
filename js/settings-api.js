/* global wp */

jQuery(document).ready(function ($) {
    'use strict';

    /**
     * Check all upload sections for uploaded files
     */
    $('code.uploaded-file-url').each(function () {
        if ($(this).html().trim() !== '') {
            $(this).css('display', 'inline-block');
        }
    });

    $('img.uploaded-image').each(function () {
        if ($(this).attr('src').trim() !== '') {
            $(this).css('display', 'block');
        }
    });

    // Upload attachment
    $('.upload, .image img, .url code').click(function (e) {
        e.preventDefault();

        let sendAttachmentBkp = wp.media.editor.send.attachment;
        let dataID = $(this).data('field-id');

        wp.media.editor.send.attachment = function (props, attachment) {
            let current = '[data-id="' + dataID + '"]';

            if (attachment.sizes && attachment.sizes.thumbnail && attachment.sizes.thumbnail.url) {
                $(current + ' .image img').attr('src', attachment.sizes.thumbnail.url);
                $(current + ' .image img').css('display', 'block');
            }

            $(current + ' .url code').html(attachment.url).show();
            $(current + ' .attachment_id').val(attachment.id);
            $(current + ' .remove').show();
            $(current + ' .upload').hide();

            wp.media.editor.send.attachment = sendAttachmentBkp;
        };

        wp.media.editor.open();

        return false;
    });

    // Remove attachment
    $('.remove').click(function (e) {
        e.preventDefault();

        let dataID = $(this).parent().attr('data-id');
        let current = '[data-id="' + dataID + '"]';

        $(current + ' .url code').html('').hide();
        $(current + ' .attachment_id').val('');
        $(current + ' .image img').attr('src', '');
        $(current + ' .image img').css('display', 'none');
        $(current + ' .remove').hide();
        $(current + ' .upload').show();
    });

    // Add color picker to fields
    if ($('.colorpicker').length) {
        $('.colorpicker').wpColorPicker();
    }

    // Nav click toggle
    if ($('.nav-tab').length) {
        $('.nav-tab').click(function (e) {
            e.preventDefault();

            let id = $(this).attr('href').substr(1);

            $('.tab-content').hide();
            $('#' + id).show();

            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
        });
    }
});
