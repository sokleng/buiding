 var Building = (function() {
    "use strict";
    const FORM = 'switch_currency_';

    var show_modal_switch_currency = function(elem) {
        var condominiumId = $(elem).attr('condominium-id');
        var currencyId = $(elem).attr('currency-id').trim();
        $('#'+ FORM +'currency').val(currencyId);
        $('#'+ FORM + 'condominiumId').val(condominiumId);
        $('#modal-action').modal('show');
    };

    return {
        show_modal_switch_currency: show_modal_switch_currency
    };
}());
