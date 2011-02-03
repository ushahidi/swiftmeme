<head>
<?php echo(Html::script("media/js/protochart/prototype.js")."\n"); ?>
<?php echo(Html::script("media/js/protochart/ProtoChart.js")."\n"); ?>
</head>
<body>
<?php
    $json_decoded = json_decode($json);
    $json_data = $json_decoded->data;
    $sample_channel_name = "";

    $min_day_range = 0;
    $max_day_range = 0;

    $peak_statistic = 0;

    if(is_null($json_data) || $json_data == "null") {
        // There should not be anything else to display
        echo('<div style="text-align:center;">No data to display</div>');
    }
    else {
        // Process the content items
        if(count($json_data) == 0) {
            echo('<div style="text-align:center;">No data to display</div>');
        }
        else {
            $channel_data = array();
            $days_data = array();
            $first_round = true;

            foreach($json_data as $data_item) {
                $day_of_year = explode("-", $data_item->dayOfTheYear);
                $day_of_year = $day_of_year[0];

                $number_of_content_items = $data_item->numberOfContentItems;
                $channel_id = $data_item->channelId;
                $channel_name = $data_item->channelName;

                // Create a "clean" channel name
                $channel_name = str_replace("@", "", $channel_name);
                $channel_name = str_replace(" ", "_", $channel_name);

                $channel_exists = false;
                $day_exists = false;

                $day_exists_in_array = false;

                if($first_round) {
                    $min_day_range = intval($day_of_year);

                    $first_round = false;
                }

                // Check: to see if a channel exists

                foreach($channel_data as $channel) {
                    if(isset($channel[$channel_name]["channel_name"])) {
                        // The channel we want exists
                        $channel_exists = true;

                        foreach($channel[$channel_name] as $channel_data_entry) {
                            if(isset($channel_data_entry[$day_of_year]))
                                // The channel we want has an entry for the date
                                $day_exists = true;
                        }
                    }
                }

                // Check: to see if the day exists in our array

                foreach($days_data as $day_data) {
                    if($day_data == $day_of_year)
                        $day_exists_in_array = true;
                }

                // Insert day of year in days array if not existent

                if(!$day_exists_in_array) {
                    $days_data[] = $day_of_year;
                }

                // Check for min and max day values

                if(intval($day_of_year) < $min_day_range) {
                    $min_day_range = intval($day_of_year);
                }

                if(intval($day_of_year) > $max_day_range) {
                    $max_day_range = intval($day_of_year);
                }

                // Insert data into our data array

                if($channel_exists) {
                    if($day_exists) {
                        $channel_data[$channel_name][$day_of_year] += intval($number_of_content_items);
                    }
                    else {
                        $channel_data[$channel_name][$day_of_year] = intval($number_of_content_items);
                    }
                }
                else {
                    $channel_data[$channel_name]["channel_name"] = $channel_name;
                    $channel_data[$channel_name][$day_of_year] = intval($number_of_content_items);
                }

                $statistic = $channel_data[$channel_name][$day_of_year];

                if($statistic > $peak_statistic) {
                    $peak_statistic = $statistic;
                }
            }

            // Some important global variables (for this page)

            $current_date = intval(date('d'));

            $flows_to_next_month = false;

            $last_new_month_day =  $current_date;
            $first_old_month_day = $max_day_range - ($timelimit - $last_new_month_day);

            // $min_day_range = $first_old_month_day;

            if(($last_new_month_day - count($days_data)) < 0) {
                $flows_to_next_month = true;
            }

            /*
            if($min_day_range < 0) {
                $min_day_range = $min_day_range + ($timelimit - count($days_data));
            }
             * 
             */
?>
<script type="text/javascript">
    // Prepare the data
<?php
        $var_entries = "";

        foreach($channel_data as $channel) {
            // Construct a new variable for a new channel
            $var_entries.="\tvar ".$channel["channel_name"]." = [";

            foreach($days_data as $day_data) {
                // Construct an "each day" entry
                if(!$flows_to_next_month) {
                    if(isset($channel[$day_data])) {
                        $var_entries.="[".$day_data.", ".$channel[$day_data]."],";
                    }
                    else {
                        $var_entries.="[".$day_data.", 0],";
                    }
                }
                else {
                    $new_day = $day_data;

                    if(intval($day_data) <= $last_new_month_day) {
                        $new_day = $day_data + $max_day_range;
                    }

                    if(isset($channel[$day_data])) {
                        $var_entries.="[".$new_day.", ".$channel[$day_data]."],";
                    }
                    else {
                        $var_entries.="[".$new_day.", 0],";
                    }
                }
            }

            $var_entries = rtrim($var_entries, ",");
            $var_entries.="];\n";
        }
?>
    // Render the graph
    Event.observe(window, 'load', function() {
<?php echo($var_entries); ?>
        new Proto.Chart($('barchart'),
        [
<?php
    $data_entries = "";

    foreach($channel_data as $channel) {
        $data_entries.="\t\t{data:". $channel["channel_name"].", label:\"".$channel["channel_name"]."\"},\n";
    }

    $data_entries = rtrim($data_entries, ",\n");
    echo($data_entries."\n");
?>
        ],
        {
            xaxis: {
                min: <?php echo($min_day_range); ?>,
                max: <?php echo($max_day_range); ?>,
                ticks: [
<?php
        $tick_entries = "";

        $start_day = $min_day_range;

        if(!$flows_to_next_month) {
            // Do the normal graph
            foreach($days_data as $day_data) {
                $tick_entries.="\t\t\t\t[".$day_data.",'".intval($day_data)."'],\n";
            }
        }
        else {
            // Some computation needed here

            for($old_month_loop = $first_old_month_day; $old_month_loop <= $max_day_range; $old_month_loop ++) {
                $tick_entries.="\t\t\t\t[".$old_month_loop.",'".$old_month_loop."'],\n";
            }

            for($new_month_loop = 1; $new_month_loop <= $last_new_month_day; $new_month_loop ++) {
                $new_day = $new_month_loop + $max_day_range;
                $tick_entries.="\t\t\t\t[".$new_day.",'".$new_month_loop."'],\n";
            }
        }

        $tick_entries = rtrim($tick_entries, ",\n");
        echo($tick_entries."\n");
?>
                ]
            },
            yaxis: {
                max: <?php echo($peak_statistic); ?>
            },
            grid: {
                drawXAxis: true,
                drawYAxis: true
<?php
    if(count($days_data) == 1) {
            // If theres only one day we cant show a graph. Plot points
?>
            },
            points: {
                show: true
            }
<?php
    }
    else {
?>
            }
<?php
    }
?>
        });
    });
</script>
<?php
        // End of data display
        }
    }
?>
<div id="barchart" style="width:200px;height:200px"></div>
</body>