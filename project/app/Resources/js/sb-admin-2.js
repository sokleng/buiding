const COOKIE_COLLAPSE_MENU_STATUS = 'collapseMenuStatusCookie';
const CLASS_NAVSIDEBAR_FIXED_LEFT = '.navsidebar-fixed-left';
const NAVSIDEBAR_COLLAPSED = 'navsidebar-collapsed';
const CLASS_MAIN_CONTENT_AREA = '.main-content-area';
const CONTENT_EXPANDED = 'content-expanded';
const CLASS_COLLAPSE_ICON = '.collapse-icon';
const CLASS_GLYPHICON_MENU_LEFT = '.glyphicon-menu-left';
const GLYPHICON_MENU_RIGHT = 'glyphicon-menu-right';
const CLASS_COLLAPSE_TEXT = '.collapse-text';
const CLASS_EXPAND_TEXT = '.expand-text';
const SMALL = 'small';
const FULL = 'full';
const COOKIE_PATH = '/';

$(function() {
    $('#side-menu').metisMenu();
    if($.cookie(COOKIE_COLLAPSE_MENU_STATUS) == SMALL) {
        toggle_menu(true);
    }
});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function() {
    $(window).bind("load resize", function() {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    var url = window.location;
    var element = $('ul.nav a').filter(function() {
        return this.href == url || url.href.indexOf(this.href) == 0;
    }).addClass('active').parent().parent().addClass('in').parent();
    if (element.is('li')) {
        element.addClass('active');

        element.parents('.nav-second-level').addClass('in');
    }

    $('.trigger-btn').click(function(){
        if($(CLASS_NAVSIDEBAR_FIXED_LEFT).hasClass(NAVSIDEBAR_COLLAPSED)) {
            toggle_menu(false);
        } else {
            toggle_menu(true);
        }
    });

});

var toggle_menu = function(status) {
    if (status) {
        $(CLASS_NAVSIDEBAR_FIXED_LEFT).addClass(NAVSIDEBAR_COLLAPSED)
        $(CLASS_MAIN_CONTENT_AREA).addClass(CONTENT_EXPANDED)
        $(CLASS_COLLAPSE_ICON).find(CLASS_GLYPHICON_MENU_LEFT).addClass(GLYPHICON_MENU_RIGHT);
        $(CLASS_COLLAPSE_TEXT).show();
        $(CLASS_EXPAND_TEXT).show();
        $.cookie(COOKIE_COLLAPSE_MENU_STATUS, SMALL, { path: COOKIE_PATH });
    } else {
        $(CLASS_NAVSIDEBAR_FIXED_LEFT).removeClass(NAVSIDEBAR_COLLAPSED)
        $(CLASS_MAIN_CONTENT_AREA).removeClass(CONTENT_EXPANDED)
        $(CLASS_COLLAPSE_ICON).find(CLASS_GLYPHICON_MENU_LEFT).removeClass(GLYPHICON_MENU_RIGHT);
        $(CLASS_COLLAPSE_TEXT).hide();
        $(CLASS_EXPAND_TEXT).hide();
        $.cookie(COOKIE_COLLAPSE_MENU_STATUS, FULL, { path: COOKIE_PATH });
    }
}
