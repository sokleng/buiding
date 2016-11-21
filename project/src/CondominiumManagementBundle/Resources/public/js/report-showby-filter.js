var ReportShowbyFilter = (function (){
    "use strict";

    var filter_showby = function() {
        var showby = $('#condominium_report_filter_showby').val();
        var uri = location.href.split('?')[0];
        window.location.href = update_query_string_parameter(uri, 'condominium_report_filter[showby]', showby);
    }

    var filter_status_showby = function() {
        var showby = $('#condominium_report_filter_status_showby').val();
        var uri = location.href.split('?')[0];
        window.location.href = update_query_string_parameter(uri, 'condominium_report_filter_status[showby]', showby);
    }

    var update_query_string_parameter = function (uri, key, value) {
        var regExp = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = uri.indexOf('?') !== -1 ? "&" : "?";
        if (uri.match(regExp)) {
            return uri.replace(regExp, '$1' + key + "=" + value + '$2');
        } else {
            return uri + separator + key + "=" + value;
        }
    }

    var datepicker_conf = function() {
        $('.date-picker').datepicker();
    }

    return {
        filter_showby: filter_showby,
        filter_status_showby: filter_status_showby,
        datepicker_conf: datepicker_conf
    };
}());

document.addEventListener('DOMContentLoaded', ReportShowbyFilter.datepicker_conf, false);
