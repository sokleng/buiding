 var SpaceModules = (function() {
    "use strict";

    var list_space_and_modules = function() {
        var userId = $("#user_space").attr('data-user');
        var roleType = $("#user_space").val();
        var route = $("#user_space").attr('data-route');
        var url = route + '/' + roleType;
        var data = {roleType};

        if(userId !== undefined){
            url += '/' + userId
            data = {roleType, userId};
        }

        $.ajax({
            url: url,
            data: data,
            method: 'POST',
            success: function(html) {
                $("#list-modules").html(html);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    };

    return {
        list_space_and_modules: list_space_and_modules
    };

}());

document.addEventListener('DOMContentLoaded', SpaceModules.list_space_and_modules, false);

