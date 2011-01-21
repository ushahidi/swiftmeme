<?php
    $content_exists = false;
    $json_decoded = json_decode($json);

    $content_items = null;

    // Get total number of items
    if($json_decoded->message == "OK") {
        $content_exists = true;
        $content_items = $json_decoded->data->sources;
    }
?>
<html>
  <head>
    <title>Content Item mapping</title>
    <?php echo(Html::script('media/js/protovis/protovis-r3.2.js')); ?>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=ABQIAAAA_l8gzbi6C7crybKcN7JbkxQ52V7FrU9BtBAX9VeidCyofjK-ERTUSK0vM-1ZP1eEA-Q1Rh5jyj_D9A" type="text/javascript"></script>
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
    <script type="text/javascript+protovis">

<?php
    if($content_exists) {
?>

    var source_locations = [
<?php
        $current_item = 0;
        foreach($content_items as $content_item) {
            $item_date = date("Y-m-d\TH:i:s", $content_item->date);
            
            if($content_item->type == "Twitter") {
                $current_item ++;
                if(is_array($content_item->gisData)) {
                    if(isset($content_item->gisData[0]->latitude) && isset($content_item->gisData[0]->longitude)) {
?>
      { id: "<?php echo($current_item); ?>", code: "Tw", date: "<?php echo($item_date); ?>", lat: <?php echo($content_item->gisData[0]->latitude); ?>, lon: <?php echo($content_item->gisData[0]->longitude); ?> },
<?php
                    }
                }

            }
        }
?>
    ];

    var source_codes = [
      { code: "Tw", name: "Tweet", category: "tweet" },
    ];

    var colors = {
      tweet: { light: "rgba(217, 0, 0, .8)", dark: "rgb(163, 0, 0)" },
    };

    source_codes.forEach(function(x) colors[x.code] = colors[x.category]);

    function Canvas(source_locations) {
      this.source_locations = source_locations;
    }

    Canvas.prototype = pv.extend(GOverlay);

    Canvas.prototype.initialize = function(map) {
      this.map = map;
      this.canvas = document.createElement("div");
      this.canvas.setAttribute("class", "canvas");
      map.getPane(G_MAP_MAP_PANE).parentNode.appendChild(this.canvas);
    };

    Canvas.prototype.redraw = function(force) {
      if (!force) return;
      var c = this.canvas, m = this.map, r = 20;

      /* Get the pixel locations of the crimes. */
      var pixels = this.source_locations.map(function(d) {
          return m.fromLatLngToDivPixel(new GLatLng(d.lat, d.lon));
        });

      /* Update the canvas bounds. Note: may be large. */
      function x(p) p.x; function y(p) p.y;
      var x = { min: pv.min(pixels, x) - r, max: pv.max(pixels, x) + r };
      var y = { min: pv.min(pixels, y) - r, max: pv.max(pixels, y) + r };
      c.style.width = (x.max - x.min) + "px";
      c.style.height = (y.max - y.min) + "px";
      c.style.left = x.min + "px";
      c.style.top = y.min + "px";

      /* Render the visualization. */
      new pv.Panel()
          .canvas(c)
          .left(-x.min)
          .top(-y.min)
        .add(pv.Panel)
          .data(this.source_locations)
        .add(pv.Dot)
          .left(function() pixels[this.parent.index].x)
          .top(function() pixels[this.parent.index].y)
          .strokeStyle(function(x, d) colors[d.code].dark)
          .fillStyle(function(x, d) colors[d.code].light)
          .size(140)
        .anchor("center").add(pv.Label)
          .textStyle("white")
          .text(function(x, d) d.code)
        .root.render();
    };

    /* Restrict minimum and maximum zoom levels. */
    G_NORMAL_MAP.getMinimumResolution = function() 11;
    G_NORMAL_MAP.getMaximumResolution = function() 14;

    var map = new GMap2(document.getElementById("map"));
    map.setCenter(new GLatLng(37.78, -122.22), 12);
    map.setUI(map.getDefaultUI());
    map.addOverlay(new Canvas(source_locations));

<?php
    }
?>
    </script>
  </head>
  <body onunload="GUnload()">
    <div id="map"></div>
  </body>
</html>