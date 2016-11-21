 var Vat = (function() {
    "use strict";

    var show_rate_and_vat_tin = function() {
        $('#rate').hide();
        $('#vatTin').hide();
        if($('#condominium_isVat, #service_provider_isVat').is(':checked')){
            $('#vatTin').show();
            $('#rate').show();
        }
    };

    return {
        show_rate_and_vat_tin: show_rate_and_vat_tin
    };
}());

window.onload = function () {
    Vat.show_rate_and_vat_tin();
}
