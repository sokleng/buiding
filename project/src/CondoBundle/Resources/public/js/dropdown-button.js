var DropdownButton = (function() {
    'use strict'

    var toggle_class_table_responsive = function (elem) {
        var btnGroup = $(elem).closest('.btn-group');
        var tableDropdownButton = $(elem).closest('.table-dropdown-button');
        var time = 200;
        if(btnGroup.hasClass('open')) {
            setTimeout(function(){
                tableDropdownButton.addClass('table-responsive');
            }, time)
        } else {
            tableDropdownButton.removeClass('table-responsive');
        }
    }

    return {
        toggle_class_table_responsive : toggle_class_table_responsive
    };
}());
