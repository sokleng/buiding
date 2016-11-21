var FilterClient = (function() {
    "use strict";

    var filter_client = function() {
        $("#client-unit-filter-form").submit();
    }

    var datepicker_change = function() {
        $('.datepicker').on('dp.change', function(e){
            $("#client-unit-filter-form").submit();
        });
    }

    return {
        filter_client: filter_client,
        datepicker_change : datepicker_change
    };

}());

document.addEventListener('DOMContentLoaded', FilterClient.datepicker_change, false);
