<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ahmed
 * Date: 2/21/11
 * Time: 12:01 PM
 * To change this template use File | Settings | File Templates.
 */
    $channel_data = $params["channel_data"];
    $days_data = $params["days_data"];
    $flows_to_next_month = $params["flows_to_next_month"];
    $data_exists = $params["data_exists"];

    $min_day_range = $params["min_day_range"];
    $max_day_range = $params["max_day_range"];
    $peak_statistic = $params["peak_statistic"];

    asort($days_data);

    if(!$data_exists || ($min_day_range == $max_day_range)) {
        echo('<div style="text-align:center;">No data to display</div>');
    }
    else {
?>
<script type="text/javascript">
<?php
    $var_entries = "";

    foreach($channel_data as $channel) {
        // Construct a new variable for a new channel
        $var_entries.="\tvar tk_".$channel["channel_name"]." = [";

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
    echo($var_entries);
?>
<?php
    // Ticks
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
    echo("tk_xticks=[".$tick_entries."];\n");
?>
<?php
    // Channel list
    $channels_to_display = "";

    foreach($channel_data as $channel)
    {
        $channels_to_display.="tk_".$channel["channel_name"].",";
    }

    $channels_to_display = rtrim($channels_to_display,",");
?>
        trending_keywords_plot = $.jqplot('trending-keywords-widget', [<?php echo($channels_to_display); ?>], {
            title:'TRENDING KEYWORDS',
            grid: {background:'#f3f3f3', gridLineColor:'#accf9b'},
            series:[
<?php
    foreach($channel_data as $channel) {
?>
                {label:'<?php echo($channel["channel_name"]); ?>'},
<?php
    }
?>
        ],
        axes:{
            xaxis:{ticks:tk_xticks,label:"Days of the month"}
        }
    });
</script>
<?php } ?>