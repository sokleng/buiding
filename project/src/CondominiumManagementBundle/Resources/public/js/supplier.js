var Supplier = (function() {
    "use strict";

    var SUPPLIER = 'condominium_company_';
    var is_vat = function() {
        var vatin = document.getElementById(SUPPLIER + 'supplier_vatin');
        var companyVatin = document.getElementById('company-vatin');
        if(document.getElementById('is-vat').checked == true) {
            companyVatin.classList.remove('hide');
            vatin.required = true;

            return;
        }
        if(document.getElementById(SUPPLIER + 'supplier_vatin').value !== '') {
            document.getElementById('is-vat').checked = true;
            companyVatin.classList.remove('hide');
            vatin.required = true;

            return;
        }
        companyVatin.classList.add('hide');
        vatin.required = false;
    }

    return {
        is_vat: is_vat
    };

}());

document.addEventListener(
    'DOMContentLoaded',
    function() {
        Supplier.is_vat();
    },
    false
);
