<?php
    $content_exists = false;
    $json_decoded = json_decode($json);

    $content_items = null;

    // Get total number of items
    if($json_decoded->message == "OK") {
        $content_exists = true;
        $content_items = $json_decoded->data->sources;
    }

    $center_lat = 0;
    $center_lon = 0;

    $first_round = true;
?>
<html>
  <head>
    <title>Content Item mapping</title>
    <style type="text/css">
        body {
          margin: 0;
        }

        #map {
          height: 100%;
        }

        #map .canvas {
          position: absolute;
        }
    </style>
    <!-- Google maps -->
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <!-- Map code -->
    <script type="text/javascript">
<?php
    if($content_exists) {
?>
        var map;

        function addMarkers() {
            var bounds = map.getBounds();
            var southWest = bounds.getSouthWest();
            var northEast = bounds.getNorthEast();
            var lngSpan = northEast.lng() - southWest.lng();
            var latSpan = northEast.lat() - southWest.lat();
<?php
        $current_item = 0;
        foreach($content_items as $content_item) {
            $item_date = date("Y-m-d\TH:i:s", $content_item->date);
            
            if($content_item->type == "Twitter") {
                $current_item ++;
                if(is_array($content_item->gisData)) {
                    if(isset($content_item->gisData[0]->latitude) && isset($content_item->gisData[0]->longitude)) {
                        // Center coordinates
                        if($first_round) {
                            $center_lat = $content_item->gisData[0]->latitude;
                            $center_lon = $content_item->gisData[0]->longitude;

                            $first_round = false;
                        }
?>
            // Add the coordinates / Markers at this point

            var latLng_<?php echo($current_item); ?> = new google.maps.LatLng(<?php echo($$content_item->gisData[0]->latitude); ?>,
                                              <?php echo($$content_item->gisData[0]->longitude); ?>);
            var marker_<?php echo($current_item); ?> = new google.maps.Marker({
                position: latLng_<?php echo($current_item); ?>,
                map: map
            });
<?php
                    }
                }
            }
        }
?>
        }

        function initialize() {
            var mapDiv = document.getElementById('map-canvas');
            map = new google.maps.Map(mapDiv, {
              center: new google.maps.LatLng(<?php echo($center_lat); ?>, <?php echo($center_lon); ?>),
              zoom: 13,
              mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            google.maps.event.addListenerOnce(map, 'tilesloaded', addMarkers);
        }

        google.maps.event.addDomListener(window, 'load', initialize);
<?php
    }
?>
    </script>
  </head>
  <body onunload="GUnload()">
    <!-- Place the map div here -->
    <div id="map-canvas" style="width: 200px; height: 150px"></div>
  </body>
</html>