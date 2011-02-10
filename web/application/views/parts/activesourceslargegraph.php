<head>
<?php echo(Html::script("media/js/protochart/prototype.js")."\n"); ?>
<?php echo(Html::script("media/js/protochart/ProtoChart.js")."\n"); ?>
</head>
<body>
<?php
    $source_data = $params["source_data"];
    $days_data = $params["days_data"];
    $flows_to_next_month = $params["flows_to_next_month"];
    $data_exists = $params["data_exists"];

    $min_day_range = $params["min_day_range"];
    $max_day_range = $params["max_day_range"];
    $peak_statistic = $params["peak_statistic"];

    if(!$data_exists) {
        echo('<div style="text-align:center;">No data to display</div>');
    }
    else {
?>
    <script type="text/javascript">
        // Prepare the data
<?php
        $var_entries = "";

        foreach($source_data as $source) {
            // Construct a new variable for a new channel
            $var_entries.="\tvar ".$source["source_name"]." = [";

            foreach($days_data as $day_data) {
                // Construct an "each day" entry
                if(!$flows_to_next_month) {
                    if(isset($source[$day_data])) {
                        $var_entries.="[".$day_data.", ".$source[$day_data]."],";
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
                        $var_entries.="[".$new_day.", ".$source[$day_data]."],";
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

        foreach($source_data as $source) {
            $data_entries.="\t\t{data:". $source["source_name"].", label:\"".$source["source_name"]."\"},\n";
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

        if(!$flows_to_next_month) {
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
            legend: {
                show: true
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
            },
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
    <div id="barchart" style="width:700px;height:500px"></div>
<?php
        // End of data display
    }
?>
</body>