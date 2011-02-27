<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ahmed
 * Date: 2/21/11
 * Time: 12:01 PM
 * To change this template use File | Settings | File Templates.
 */
    $source_data = $params["source_data"];
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


    foreach($source_data as $source) {
        // Construct a new variable for a new channel
        $var_entries.="\tvar as_".$source["source_name"]." = [";

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

                if(isset($source[$day_data])) {
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
    echo("as_xticks=[".$tick_entries."];\n");
?>
<?php
    // Channel list
    $sources_to_display = "";

    foreach($source_data as $source)
    {
        $sources_to_display.="as_".$source["source_name"].",";
    }

    $sources_to_display = rtrim($sources_to_display,",");
?>
        active_sources_plot = $.jqplot('active-sources-widget', [<?php echo($sources_to_display); ?>], {
            title:'ACTIVE SOURCES',
            grid: {background:'#f3f3f3', gridLineColor:'#accf9b'},
            series:[
<?php
    foreach($source_data as $source) {
?>
                {label:'<?php echo($source["source_name"]); ?>'},
<?php
    }
?>
        ],
        axes:{
            xaxis:{ticks:as_xticks}
        }
    });
</script>
<?php } ?>