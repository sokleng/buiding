var UnitTypes = (function(){
'use strict';

    /*
     * AutoLoad list or grid of view of unit types
     * @public
     */
    var auto_view = function () {
        var view = localStorage.getItem('view');
        if (view === '' || view === undefined || view === null) {
            localStorage.setItem('view', 'list');
        }

        if(view === 'list') {
            show_as_list();
            return;
        }
        if(view === 'grid') {
            show_as_grid();
            return;
        }
    };

    /*
     * Click on list and grid icon to switch the view of unit types
     * @public
     */

    var switch_view = function () {

        if ($('#list-units').is(':hidden'))
        {
            show_as_list();
            return;
        }

        if ($('#grid-units').is(':hidden'))
        {
            show_as_grid();
            return;
        }
    };

    /*
     * show UnitTypes as list
     * @private
     */
    var show_as_list = function () {
        localStorage.setItem('view', 'list');
        $('#switch-button').removeClass('glyphicon-th-large');
        $('#switch-button').addClass('glyphicon-th-list');
        $('#grid-units').hide();
        $('#list-units').show();
    };

    /*
     * show UnitTypes as grid
     * @private
     */
    var show_as_grid = function () {
        localStorage.setItem('view', 'grid');
        $('#switch-button').addClass('glyphicon-th-large');
        $('#switch-button').removeClass('glyphicon-th-list');
        $('#list-units').hide();
        $('#grid-units').show();
    }

    return {
        switch_view : switch_view,
        auto_view : auto_view
    };

}());

window.onload = function () {
    UnitTypes.auto_view();
}
