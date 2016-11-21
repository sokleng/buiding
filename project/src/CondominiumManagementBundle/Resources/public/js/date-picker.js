var DatePicker = (function(){
    'use strict';
    var datepicker_conf = function() {
        $('.datepicker').datetimepicker({
            format: 'DD-MM-YYYY HH:mm'
        });
    }

    return {
        datepicker_conf : datepicker_conf
    }

}());

document.addEventListener('DOMContentLoaded', DatePicker.datepicker_conf, false);
