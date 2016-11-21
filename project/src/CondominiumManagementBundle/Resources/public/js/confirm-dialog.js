 var ConfirmDialog = (function() {
    "use strict";

    var show_modal_confirm_dialog = function(elem) {
        var action = $(elem).attr('action');

        $('#modal-action').modal('show');
        $('#save').attr('href', action);
    };

    return {
        show_modal_confirm_dialog: show_modal_confirm_dialog
    };
}());
