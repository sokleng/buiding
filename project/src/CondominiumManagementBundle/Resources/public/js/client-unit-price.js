var ClientUnitPrice = (function(){
    "use strict";

    var show_popup_modal = function() {
        $('#modal-action').modal();
    }

    var show_edit_manual_price = function() {
        document.getElementById('edit-price-manual').classList.remove('hide');
        document.getElementById('btn-edit-price-manual').classList.add('hide');
        document.getElementById('get-price-from-unit').classList.add('hide');
    }

    var hide_edit_manual_price = function() {
        document.getElementById('edit-price-manual').classList.add('hide');
        document.getElementById('btn-edit-price-manual').classList.remove('hide');
        document.getElementById('get-price-from-unit').classList.remove('hide');
    }

    return {
        show_popup_modal: show_popup_modal,
        show_edit_manual_price: show_edit_manual_price,
        hide_edit_manual_price: hide_edit_manual_price
    };
}());

document.addEventListener('DOMContentLoaded', ClientUnitPrice.hide_edit_manual_price, false);
