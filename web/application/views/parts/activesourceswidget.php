<?php
    $json_decoded = json_decode($json);
    $json_data = $json_decoded->data;

    if(is_null($json_data) || $json_data == "null") {

        // There should not be anything else to display
        echo('<div style="text-align:center;font-weight:bold;">ACTIVE SOURCES</div><div style="text-align:center;">No data to display</div>');
    }
    else {
        $channels = array();
        $sources = array();

        // Fill in the channel and source names
        foreach($json_data as $data_item) {
            $channel_exists = false;
            $source_exists = false;

            foreach($channels as $channel) {
                if($channel == $data_item->channelName)
                    $channel_exists = true;
            }

            foreach($sources as $source) {
                if($source == $data_item->sourceName)
                    $source_exists = true;
            }

            if(!$channel_exists) {
                $channels[] = $data_item->channelName;
            }

            if(!$source_exists) {
                $sources[] = $data_item->sourceName;
            }
        }
?>
<div style="text-align:center;font-weight:bold;">ACTIVE SOURCES</div>
<script type="text/javascript+protovis">

<?php
        $channel_list = "";

        foreach($channels as $channel) {
            $channel_list.='"'.$channel.'",';
        }

        rtrim($channel_list, ",");
?>
    // Prepare the data
    var source_channels = [<?php echo($channel_list);?>];

    var source_channels_data = [
<?php
        // Fill in the channel names
        foreach($json_data as $data_item) {
            echo('{ dayOfYear: '.$data_item->dayOfTheYear.', '.$data_item->channelName.': "'.$data_item->numberOfSources.'"},\n');
        }
?>
    ];

    // Render the graph
    var w = 250,
        h = 100,
        x = pv.Scale.linear(source_channels_data, function(d) d.date).range(0, w),
        y = pv.Scale.linear(0, 1500).range(0, h),
        fill = pv.colors("lightpink", "darkgray", "lightblue"),
        format = pv.Format.date("%b");

    var vis2 = new pv.Panel()
        .width(w)
        .height(h)
        .margin(19.5)
        .right(40);

    vis2.add(pv.Panel)
        .data(source_channels)
      .add(pv.Line)
        .data(source_channels_data)
        .left(function(d) x(d.date))
        .bottom(function(d, t) y(d[t]))
        .strokeStyle(fill.by(pv.parent))
        .lineWidth(3);

    vis2.add(pv.Label)
        .data(x.ticks())
        .left(x)
        .bottom(0)
        .textBaseline("top")
        .textMargin(5)
        .text(pv.Format.date("%b"));

    vis2.add(pv.Rule)
        .data(y.ticks())
        .bottom(y)
        .strokeStyle(function(i) i ? "#ccc" : "black")
      .anchor("right").add(pv.Label)
        .visible(function() !(this.index & 1))
        .textMargin(6);

    vis2.render();
</script>
<?php
    }
?>