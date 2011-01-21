<?php
    $json_decoded = json_decode($json);
    $json_data = $json_decoded->data;

    $min_day_range = 0;
    $max_day_range = 0;

    $peak_statistic = 0;

    if(is_null($json_data) || $json_data == "null") {
        
        // There should not be anything else to display
        echo('<div style="text-align:center;font-weight:bold;">TRENDING KEYWORDS</div><div style="text-align:center;">No data to display</div>');
    }
    else {
        $channels = array();
        $channel_total = array();

        // Fill in the channel names

        foreach($json_data as $data_item) {
            $channel_exists = false;
            $date_exists = false;

            $channel_name = "";
            $channel_date = "";
            $current_index = 0;

            $current_statistic = 0;

            foreach($channels as $channel) {
                if(isset($channel["swiftchannelname"])) {
                    // has the channel name been defined?
                    if($channel["swiftchannelname"] == $data_item->channelName) {
                        $channel_exists = true;
                        $channel_name = $channel["swiftchannelname"];

                        foreach($channel as $channel_data_item) {
                            // is it the channel name array element?
                            if(!isset($channel["swiftchannelname"])) {
                                // it is the day of year and values entry
                                foreach($channel_data_item as $channel_data_item_item) {
                                    if($channel_data_item["dayofyear"] == $data_item->dayOfTheYear) {
                                        $date_exists = true;
                                        $channel_date = $channel_data_item["dayofyear"];
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $day_of_year = null;

            if(!$channel_exists) {
                $current_index ++;
                $channels[$data_item->channelName]["swiftchannelname"] = $data_item->channelName;
                $channels[$data_item->channelName][$data_item->dayOfTheYear]["numberofitems"] = $data_item->numberOfContentItems;
                $channels[$data_item->channelName][$data_item->dayOfTheYear]["dayofyear"] = $data_item->dayOfTheYear;

                $current_statistic = $data_item->numberOfContentItems;

                $day_of_year = explode("-", $data_item->dayOfTheYear);
            }
            else {
                if($date_exists) {
                    $channels[$channel_name][$channel_date]["numberofitems"] += $data_item->numberOfContentItems;
                }
                else {
                    $channels[$channel_name][$channel_date]["numberofitems"] = $data_item->numberOfContentItems;
                }

                $current_statistic = $channels[$channel_name][$channel_date]["numberofitems"];

                $day_of_year = explode("-", $channel_date);
            }

            if($current_statistic > $peak_statistic) {
                $peak_statistic = $current_statistic; 
            }
        }
        
        foreach($channels as $channel) {
            $channels[$channel["swiftchannelname"]]["swiftchannelname"] = str_replace(" ", "_", $channel["swiftchannelname"]);
            $channels[$channel["swiftchannelname"]]["swiftchannelname"] = str_replace("@", "", $channels[$channel["swiftchannelname"]]["swiftchannelname"]);
        }
?>
<div style="text-align:center;font-weight:bold;">TRENDING KEYWORDS</div>
<script type="text/javascript+protovis">
    // Prepare the data
<?php
        $channel_list = "";

        foreach($channels as $channel) {
            $channel_list.='"'.$channel["swiftchannelname"].'",';
        }

        $channel_list = rtrim($channel_list, ',');
?>
    var channels = [<?php echo($channel_list); ?>];
<?php
        // Fill in the channel names
        $output_stats = "";

        $days_processed = array();

        $first_round = false;

        foreach($json_data as $data_item) {
            // extract the day of the year from the string
            $day_of_year = explode("-", $data_item->dayOfTheYear);
            $day_is_processed = false;

            foreach($days_processed as $day_processed) {
                if($day_processed == $day_of_year)
                    $day_is_processed = true;
            }

            if(!$day_is_processed) {
                $output_stats.='{dayOfYear:'.$day_of_year[0].',';

                foreach($channels as $channel) {
                    if(isset($channel[$data_item->dayOfTheYear])) {
                        $output_stats.=$channel["swiftchannelname"].":".$channel[$data_item->dayOfTheYear]["numberofitems"].",";
                    }
                    else {
                        $output_stats.=$channel["swiftchannelname"].":0,";
                    }
                }

                $output_stats = rtrim($output_stats, ',');
                $output_stats.='},';

                $days_processed[] = $day_of_year; 
            }

            if(!$first_round) {
                $min_day_range = $day_of_year[0];
                $first_round = true;
            }

            if($day_of_year[0] < $min_day_range) {
                $min_day_range = $day_of_year [0];
            }
            if($day_of_year[0] > $max_day_range) {
                $max_day_range = $day_of_year [0];
            }
        }

        $output_stats = rtrim($output_stats, ',');
?>
    var channel_data = [<?php echo($output_stats); ?>];
    
    // Render the graph
    var w = 200,
        h = 150,
        x = pv.Scale.linear(<?php echo($min_day_range); ?>, <?php echo($max_day_range); ?>).range(0, w),
        y = pv.Scale.linear(0, <?php echo($peak_statistic); ?>).range(0, h),
        fill = pv.colors("lightpink", "darkgray", "lightblue"),
        format = pv.Format.number();

    var vis = new pv.Panel()
        .width(w)
        .height(h)
        .margin(19.5)
        .right(40);

    vis.add(pv.Panel)
        .data(channels)
      .add(pv.Line)
        .data(channel_data)
        .left(function(d) x(d.number))
        .bottom(function(d, t) y(d[t]))
        .strokeStyle(fill.by(pv.parent))
        .lineWidth(3);

    vis.add(pv.Label)
        .data(x.ticks())
        .left(x)
        .bottom(0)
        .textBaseline("top")
        .textMargin(5)
        .text(pv.Format.number());

    vis.add(pv.Rule)
        .data(y.ticks())
        .bottom(y)
        .strokeStyle(function(i) i ? "#ccc" : "black")
      .anchor("right").add(pv.Label)
        .visible(function() !(this.index & 1))
        .textMargin(6);

    vis.render();
</script>
<?php
        // End of data display
    }
?>