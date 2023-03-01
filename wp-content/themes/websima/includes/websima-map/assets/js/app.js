jQuery(document).ready(function(){
    jQuery('.websima-map').each(function(){

        var map_id = jQuery(this).attr('id');
        var $markers = jQuery(this).find('.marker');
        var counter = 1;
        var counter2 = 0;

        var default_lat_array = [];
        var default_lng_array = [];
        var location_array = [];

        $markers.each(function(){
            default_lat_array.push(jQuery(this).attr('data-lat'));
            default_lng_array.push(jQuery(this).attr('data-lng'));

            location_array[counter2] = [jQuery(this).attr('data-lat') , jQuery(this).attr('data-lng')];
            counter2 = counter2 + 1;
        });

        default_lat = ((Math.max.apply(Math, default_lat_array) + Math.min.apply(Math, default_lat_array)) / 2);
        default_lng = ((Math.max.apply(Math, default_lng_array) + Math.min.apply(Math, default_lng_array)) / 2);


        var app_id = new Mapp({
            element: '#'+map_id,
            presets: {
                latlng: {
                    lat: default_lat,
                    lng: default_lng
                },
                zoom: 13               
            },
			apiKey: mapp_dyn_data.apiKey,
			gestureHandling: true,
			gestureHandlingOptions: {
				duration: 1200
			}
        });

        app_id.addLayers();

        /*app_id.addVectorLayers({
            base: {
                default: {
                    style: "https://map.ir/vector/styles/main/mapir-xyz-light-style.json"
                }
            }
        });*/

        /* zoom */
		app_id.addZoomControls();
		
        if(counter2 > 1){
            var bound = jQuery.map(location_array, function(value, index) {
                return [value];
            });

            app_id.map.setView([default_lat,default_lng],12);
            app_id.map.fitBounds([
                bound
            ]);
        }


        /* add marker */
        $markers.each(function(){
            var marker = app_id.addMarker({
                name: 'marker-'+counter,
                latlng: {
                    lat: jQuery(this).attr('data-lat'),
                    lng: jQuery(this).attr('data-lng')
                },
                icon: {
                    iconUrl: jQuery(this).attr('data-marker'),
                    iconSize: [79.15, 91.21],
                },
                popup: false,
            });

            /*
            var popup = app_id.generatePopupHtml({
                title: {
                    html: 'Route',
                },
                description: {
                    html: "Click on the map to change route's color.",
                },
            });

            marker.bindPopup(popup);
            */


            counter = counter + 1;
        });
    });
});



jQuery('#contact-tab a').on('click', function (e) {
   setTimeout(function() {
        window.dispatchEvent(new Event('resize'));
    }, 200);
});