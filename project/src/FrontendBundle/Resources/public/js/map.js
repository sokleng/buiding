var map;
var latitude;
var longitude;
var projectProfileName;

function initMap() {
    var liEle = $('#list-condomunium .sidebar-nav:first').children().children('li');
    var defaultLat = liEle.data('lat');
    var defaultlng =  liEle.data('lng');

    // Remove div map when condo not exit
    if( defaultlng === undefined && defaultlng === undefined ) {
        $('#map').remove();
    }

    google_map(defaultLat, defaultlng);

    $(liEle).addClass("active");

    $('.lat-and-lng').click(function() {
        // Add active menu to menu searchMap
        $('.lat-and-lng').removeClass("active");
        $(this).addClass("active");

        latitude = $(this).data('lat');
        longitude = $(this).data('lng');
        projectProfileName = $(this).data('name');

        google_map(latitude, longitude);

        var myCenter = new google.maps.LatLng(latitude, longitude);

        var marker = new google.maps.Marker({
            position:myCenter,
        });

        marker.setMap(map);

        var infowindow = new google.maps.InfoWindow({
            content: projectProfileName
        });

        google.maps.event.addListener(marker, 'click', function() {
            infowindow.open(map,marker);
        });
    });

    function google_map(latitude, longitude) {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: latitude, lng: longitude },
            zoom: 9
        });
    }
}
