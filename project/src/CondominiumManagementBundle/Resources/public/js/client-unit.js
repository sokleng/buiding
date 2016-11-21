var ClientUnit = (function() {
    "use strict";

    var CLIENT_UNIT = 'condominium_client_clientUnit_';
    var PAY_BY_DAY = '1';
    var PAY_BY_MONTH = '2';
    document.getElementById('is-send-invoice').classList.add('hide');

    var find_unit_is_locked = function(isEditForm) {
        var unitId = $('#condominium_client_clientUnit_unit').val();
        var route = $('#condominium_client_clientUnit_unit').attr('data-route');
        $.ajax({
            url: route + '/' + unitId,
            data: unitId,
            method: 'POST',
            success: function(unit) {
                load_unit(unit, isEditForm);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    };

    var load_unit = function(unit, isEditForm) {
        if (unit['isLocked']) {
            load_unit_is_locked(unit);
        } else {
            load_unit_is_not_locked(unit, isEditForm);
        }
        load_total_price();
    };

    var load_unit_is_locked = function(unit) {
        $('#' + CLIENT_UNIT + 'paymentMethod > option').each(function() {
            if($(this).val() == unit['payBy']) {
                $('#' + CLIENT_UNIT + 'paymentMethod > option').removeAttr("selected");
                $(this).prop('selected', true);
                $("#client-form > #payBy, #client-form > #unitPrice").remove();
                $("#client-form").append("<input type='hidden' id='payBy' value='"+ $(this).val() +"' name='payBy'/>");
                $("#client-form").append("<input type='hidden' id='unitPrice' value='"+ unit['price'] +"' name='unitPrice'/>");
            }
            $('#' + CLIENT_UNIT + 'paymentMethod > option').attr('disabled', 'disabled');
        });

        $("#" + CLIENT_UNIT + "unitPrice").attr('disabled', 'disabled').val(unit['price']);
    };

    var load_unit_is_not_locked = function(unit, isEditForm) {
        if(unit['payBy'] === PAY_BY_MONTH){
            document.getElementById('total-price').innerHTML = 0;
        }
        if(!isEditForm) {
            $("#client-form > #payBy, #client-form > #unitPrice").remove();
            $("#" + CLIENT_UNIT + "unitPrice").removeAttr('disabled').val('');
            $('#' + CLIENT_UNIT + 'paymentMethod > option').removeAttr("selected");
            $('#' + CLIENT_UNIT + 'paymentMethod > option').removeAttr('disabled');
            var payBy = unit["payBy"];
            var payBy = $('#' + CLIENT_UNIT + 'paymentMethod > option[value=' + payBy + ']').val();
            if(unit['payBy'] == payBy) {
                $('#' + CLIENT_UNIT + 'paymentMethod > option[value=" + payBy + "]').prop('selected', true);
            }
        }
    }

    var change_datepicker = function() {
        $('.change-datepicker').on('dp.change', function(e){
            load_total_price();
        });
    };

    var load_total_price = function() {
        var payBy = document.getElementById(CLIENT_UNIT + 'paymentMethod').value;
        if (document.getElementById('vat') !== null) {
            var vat = document.getElementById('vat').innerHTML;
        }
        var unitPrice = document.getElementById(CLIENT_UNIT + 'unitPrice').value;
        if(payBy === PAY_BY_DAY) {
            load_pay_by_day(vat, unitPrice);
        } else if(payBy === PAY_BY_MONTH) {
            load_pay_by_month(vat, unitPrice);
        } else {
            document.getElementById('total-price').innerHTML = 0;
        }
    };

    var load_pay_by_day = function(vat, unitPrice) {
        document.getElementById('label-day').classList.remove('hide');
        document.getElementById('label-month').classList.add('hide');
        if(document.getElementById(CLIENT_UNIT + 'isRunScheduleAuto').checked == true) {
            document.getElementById(CLIENT_UNIT + 'isRunScheduleAuto').checked = false;
        }
        document.getElementById('is-check-generate-invoice').classList.remove('hide');
        document.getElementById('is-run-schedule-auto').classList.add('hide');
        document.getElementById('day').classList.add('hide');
        document.getElementById('hour').classList.add('hide');
        //calculaton days
        var startDate = $('#' + CLIENT_UNIT + 'startDate').data("DateTimePicker").date();
        var endDate = $('#' + CLIENT_UNIT + 'endDate').data("DateTimePicker").date();
        var timeDiff = 0;
        if (endDate !== null) {
            timeDiff = (endDate - startDate) / 1000;
        }
        var day = Math.floor(timeDiff / (60 * 60 * 24));
        load_total_price_by_day(day, vat, unitPrice);
    };

    var load_total_price_by_day = function(day, vat, unitPrice) {
        document.getElementById('number-of-day').classList.remove('hide');
        var totalPrice = 0;
        var subTotal = 0;
        if (day != 0 && day > 0) {
            if (vat) {
                var vatPrice = unitPrice * vat/100;
                totalPrice = (parseInt(vatPrice) + parseInt(unitPrice)) * day;
                subTotal = day * unitPrice;
            } else {
                totalPrice = day * unitPrice;
                subTotal = totalPrice;
            }
            if (isNaN(totalPrice)){
                totalPrice = 0;
            }
            if (isNaN(subTotal)){
                subTotal = 0;
            }
            document.getElementById('total-price').innerHTML = totalPrice;
            document.getElementById('number-of-day').innerHTML = day;
            document.getElementById('rental-price').value = parseInt(subTotal);
            var currentLocale = document.documentElement.lang;
            if (day > 1 && currentLocale === 'en-US') {
                document.getElementById('plural').classList.remove('hide');
                document.getElementById('plural').innerHTML = 's';
            }
        } else {
            document.getElementById('total-price').innerHTML = totalPrice;
            document.getElementById('number-of-day').innerHTML = 0;
        }
    };

    var load_pay_by_month = function(vat, unitPrice) {
        document.getElementById('is-run-schedule-auto').classList.remove('hide');
        document.getElementById('label-month').classList.remove('hide');
        document.getElementById('label-day').classList.add('hide');
        is_run_schedule_auto();
        var totalPrice = 0;
        if(vat){
            var vatPrice = unitPrice * vat/100;
            totalPrice = parseInt(vatPrice) + parseInt(unitPrice);
            if (isNaN(totalPrice)){
                totalPrice = 0;
            }
        } else {
            totalPrice = unitPrice;
        }
        document.getElementById('number-of-day').classList.add('hide');
        document.getElementById('plural').classList.add('hide');
        document.getElementById('total-price').innerHTML = totalPrice;
        document.getElementById('rental-price').value = parseInt(unitPrice);
    };

    var is_check_generate_invoice = function() {
        if (document.getElementById(CLIENT_UNIT + 'generatedInvoice').checked) {
            document.getElementById('is-send-invoice').classList.remove('hide');
        } else {
            document.getElementById('is-send-invoice').classList.add('hide');
        }
    }

    var is_run_schedule_auto = function() {
        if (document.getElementById(CLIENT_UNIT + 'isRunScheduleAuto').checked) {
            document.getElementById('day').classList.remove('hide');
            document.getElementById('hour').classList.remove('hide');
            if(document.getElementById(CLIENT_UNIT + 'generatedInvoice').checked == true) {
                document.getElementById(CLIENT_UNIT + 'generatedInvoice').checked = false;
            }
            if(document.getElementById(CLIENT_UNIT + 'isSendInvoice').checked == true) {
                document.getElementById(CLIENT_UNIT + 'isSendInvoice').checked = false;
            }
            document.getElementById('is-check-generate-invoice').classList.add('hide');
            document.getElementById('is-send-invoice').classList.add('hide');
        } else {
            document.getElementById('day').classList.add('hide');
            document.getElementById('hour').classList.add('hide');
            document.getElementById('is-check-generate-invoice').classList.remove('hide');
        }
    }

    return {
        find_unit_is_locked: find_unit_is_locked,
        change_datepicker: change_datepicker,
        load_total_price: load_total_price,
        is_check_generate_invoice: is_check_generate_invoice,
        is_run_schedule_auto: is_run_schedule_auto
    };

}());

document.addEventListener(
    'DOMContentLoaded',
    function() {
        ClientUnit.find_unit_is_locked($( "#client-form" ).hasClass( "client-form-edit" ));
        ClientUnit.change_datepicker();
        ClientUnit.is_check_generate_invoice();
        ClientUnit.is_run_schedule_auto();
        ClientUnit.load_total_price();
    },
    false
);
