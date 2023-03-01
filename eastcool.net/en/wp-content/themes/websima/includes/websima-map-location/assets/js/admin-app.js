(function($){
    function initialize_field( $field ) {
        var context = $field.context;
        var key = $field.attr('data-key');
        var id = $field.parent().parent().attr('data-id');
        var main_key = $field.parents('.acf-field-repeater').attr('data-key');
        var map_field = $field.find('input[type="text"]');
        var map_id =  main_key+'_'+id+'_'+key;
        var app_id =  'app_'+main_key+'_'+id+'_'+key;
        var marker_id =  'marker_'+main_key+'_'+id+'_'+key;

        $field.find('.app').attr('id',map_id);

        jQuery(document).ready(function() {
            app_id = new Mapp({
                element: '#'+map_id,
                presets: {
                    latlng: {
                        lat: mapp_dyn_data.default_lat,
                        lng: mapp_dyn_data.default_lng,
                    },
                    zoom: mapp_dyn_data.default_zoom
                },
				apiKey: mapp_dyn_data.apiKey
            });
            app_id.addLayers();
			app_id.addZoomControls();

            marker_id = app_id.addMarker({
                name: 'initial-location',
                latlng: {
                    lat: mapp_dyn_data.default_lat,
                    lng: mapp_dyn_data.default_lng,
                },
                popup: false,
                draggable: true
            });

            marker_id.addTo(app_id.map);
            marker_id.on('dragend', function (e) {
                map_field.val(marker_id.getLatLng().lat+','+marker_id.getLatLng().lng);
            });


            jQuery(window).on('load', function(){
                var str = map_field.val();
                if(str) {
                    var latlng = str.split(",");

                    app_id.map.fitBounds([
                        latlng
                    ]);

                    app_id.removeMarkers({
                        group: app_id.groups.features.markers,
                    });

                    marker_id = app_id.addMarker({
                        name: 'loaded-location',
                        latlng: {
                            lat: latlng[0],
                            lng: latlng[1],
                        },
                        popup: false,
                        draggable: true
                    });

                    marker_id.addTo(app_id.map);
                    marker_id.on('dragend', function (e) {
                        map_field.val(marker_id.getLatLng().lat + ',' + marker_id.getLatLng().lng);
                    });
                }
            });
        });

    }


    if( typeof acf.add_action !== 'undefined' ) {

        acf.add_action('ready_field/type=map_location', initialize_field);
        acf.add_action('append_field/type=map_location', initialize_field);

    } else {

        $(document).on('acf/setup_fields', function(e, postbox){
            $(postbox).find('.field[data-field_type="map_location"]').each(function(){
                initialize_field( $(this) );
            });
        });

    }
})(jQuery);
