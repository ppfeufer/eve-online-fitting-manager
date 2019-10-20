jQuery(document).ready(function($) {
    var taxonomy = 'fitting-fleet-roles';

    $('#' + taxonomy + 'checklist li :radio, #' + taxonomy + 'checklist-pop :radio').on('click', function () {
        var t = $(this), c = t.is(':checked'), id = t.val();

        $('#' + taxonomy + 'checklist li :radio, #' + taxonomy + 'checklist-pop :radio').prop('checked', false);
        $('#in-' + taxonomy + '-' + id + ', #in-popular-' + taxonomy + '-' + id).prop('checked', c);
    });
});
