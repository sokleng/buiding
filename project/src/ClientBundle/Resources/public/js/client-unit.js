var Unit = (function() {
    "use strict";

    var switch_unit = function(elem) {
        window.location.href = elem.value;
    };

    return {
        switch_unit: switch_unit
    };

}());
