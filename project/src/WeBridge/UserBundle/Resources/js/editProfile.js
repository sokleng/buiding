var EditProfile = (function() {
'use strict';

var get_email_value_set_to_username = function(getFrom, displayTo) {
    displayTo.value =  getFrom.value;
}

return {
    get_email_value_set_to_username : get_email_value_set_to_username
}

}());
