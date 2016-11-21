var FilterStatus = (function() {
    "use strict";

    var filter_status = function() {
        var status = $('#filter-status').val();
        var uri = location.href;
        window.location.href = update_query_string_parameter(uri, 'status', status);
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

    return {
        filter_status: filter_status
    };

}());
