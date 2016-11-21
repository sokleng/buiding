var Unit = (function() {
    "use strict";

    var update_unit_price = function(ele) {
        var td = $(ele).parent().parent().parent();
        var unitId = $(ele).attr('unit');
        var price = td.find('.price').val();
        var oldPrice = td.find('.old-price').val();
        if(!isNumber(price)) {
            td.find('.input-group').addClass('has-error');
            td.find('.price').focus();

            return false;
        }
        td.find('.input-group').removeClass('has-error');
        $.ajax({
            type: 'POST',
            url: updatePriceRoute,
            dataType: 'json',
            data: {
                'unitId': unitId,
                'price': price,
                'oldPrice': oldPrice
            },
            success: function(callback) {
                if(callback.status == 500) {
                    alert(callback.message);
                }
                if(callback.status == 200) {
                    toggle_edit_price(td, false);
                    td.find('.label-price').text(callback.price);
                    td.find('.old-price').val(callback.price);
                }
            }
        });
    }

    var show_edit_price = function(ele) {
        var tr = $(ele).parent().parent();
        toggle_edit_price(tr, true);
    }

    var dismiss_edit_price = function(ele) {
        var tr = $(ele).parent().parent().parent();
        toggle_edit_price(tr, false);
    }

    var toggle_edit_price = function(tr, status) {
        if(status) {
            tr.find('.edit-price-group').removeClass('hide');
            tr.find('.label-price-group').addClass('hide');
            tr.find('.price').focus();
        } else {
            tr.find('.edit-price-group').addClass('hide');
            tr.find('.label-price-group').removeClass('hide');
        }
    }

    function isNumber(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

    return {
        update_unit_price: update_unit_price,
        show_edit_price: show_edit_price,
        dismiss_edit_price: dismiss_edit_price
    };

}());
