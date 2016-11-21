var Nationality = (function(){
    'use strict';

    var nationality_conf = function() {
         $(".nationality").countrySelect();
    }

    return {
        nationality_conf : nationality_conf
    }

}());

document.addEventListener('DOMContentLoaded', Nationality.nationality_conf, false);
