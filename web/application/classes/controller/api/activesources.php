<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_ActiveSources extends Controller
{
    // Functions to get and process data
    private function get_day_limit_json($time_limit, $day_to = 0)
    {
        $activesources_params = null;
        
        if($day_to > 0) {
            $activesources_params = array("RequestType" => "SourcesByChannelOverTime", "Parameters" => array("TimeLimit" => $time_limit, "TimeTo" =>  $day_to));
        }
        else {
            $activesources_params = array("RequestType" => "SourcesByChannelOverTime", "Parameters" => array("TimeLimit" => $time_limit));
        }
        
        $json_encoded_params = json_encode($activesources_params);
        $activesources_json = Analytics::analytics_api()->get_analysis($json_encoded_params);

        return $activesources_json;
    }

    private function get_time_limit_json($time_from, $time_to)
    {
        $activesources_params = array("RequestType" => "SourcesByChannelOverTime", "Parameters" => array("TimeRange" => "yes", "TimeFrom" => $time_from, "TimeTo" =>  $time_to));
        $json_encoded_params = json_encode($activesources_params);
        $activesources_json = Analytics::analytics_api()->get_analysis($json_encoded_params);

        return $activesources_json;
    }

    private function process_json($json, $timelimit)
    {
        $json_decoded = json_decode($json);
        $json_data = $json_decoded->data;

        // Statistical ranges
        $min_day_range = 0;
        $max_day_range = 0;
        $peak_statistic = 0;

        // Sources data
        $source_data = array();
        // Days data
        $days_data = array();
        // Does it flow to the next month
        $flows_to_next_month = false;

        $data_exists = false;

        if(is_null($json_data) || $json_data == "null") {
            // There should not be anything else to display
            $data_exists = false;
        }
        else {
            if(count($json_data) == 0) {
                $data_exists = false;
            }
            else {
                // Process the content items
                $data_exists = true;

                $first_round = true;

                foreach($json_data as $data_item) {
                    $day_of_year = explode("-", $data_item->dayOfTheYear);
                    $day_of_year = $day_of_year[0];

                    $number_of_content_items = $data_item->numberOfSources;
                    $channel_id = $data_item->channelId;
                    $source_name = $data_item->sourceName;

                    // Create a "clean" channel name
                    $source_name = str_replace("@", "", $source_name);
                    $source_name = str_replace(" ", "_", $source_name);

                    $source_exists = false;
                    $day_exists = false;

                    $day_exists_in_array = false;

                    if($first_round) {
                        $min_day_range = intval($day_of_year);

                        $first_round = false;
                    }

                    // Check: to see if a channel exists

                    foreach($source_data as $source) {
                        if(isset($source[$source_name]["channel_name"])) {
                            // The channel we want exists
                            $source_exists = true;

                            foreach($source[$source_name] as $source_data_entry) {
                                if(isset($source_data_entry[$day_of_year]))
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

                    if($source_exists) {
                        if($day_exists) {
                            $source_data[$source_name][$day_of_year] += intval($number_of_content_items);
                        }
                        else {
                            $source_data[$source_name][$day_of_year] = intval($number_of_content_items);
                        }
                    }
                    else {
                        $source_data[$source_name]["source_name"] = $source_name;
                        $source_data[$source_name][$day_of_year] = intval($number_of_content_items);
                    }

                    $statistic = $source_data[$source_name][$day_of_year];

                    if($statistic > $peak_statistic) {
                        $peak_statistic = $statistic;
                    }
                }

                // Some important global variables (for this page)

                $current_date = intval(date('d'));

                $last_new_month_day =  $current_date;
                $first_old_month_day = $max_day_range - ($timelimit - $last_new_month_day);

                if(($last_new_month_day - count($days_data)) < 0) {
                    $flows_to_next_month = true;
                    $min_day_range = $first_old_month_day + 1;
                }
            }
        }

        return array("source_data" =>  $source_data,
            "days_data" => $days_data,
            "flows_to_next_month" => $flows_to_next_month,
            "data_exists" => $data_exists,
            "min_day_range" => $min_day_range,
            "max_day_range" => $max_day_range,
            "peak_statistic" => $peak_statistic);
    }

    // Action functions
    public function action_getsources()
    {
        $time_limit = 7;

        $activesources = View::factory("parts/activesourceswidget")->set('params', $this->process_json($this->get_day_limit_json($time_limit), $time_limit));

        // Render the graph
        $this->request->response = $activesources;
    }

    public function action_getdayrangegraph($timelimit, $dayto)
    {
        $activesources = View::factory("parts/activesourcesgraph")->set('json', $this->get_day_limit_json($timelimit, $dayto))->set('timelimit', $timelimit);
        
        // Render the graph
        $this->request->response = $activesources;
    }

    public function action_gettimerangegraph($timefrom, $timeto)
    {
        $timelimit = 0;

        $time_from = intval(date('z', $timefrom));
        $time_to = intval(date('z', $timeto));

        if($time_from > $time_to) {
            if(date('L', $timefrom) == 1) {
                $timelimit = (366 - $time_from) + $time_to;
            }
            else {
                $timelimit = (365 - $time_from) + $time_to;
            }
        }
        else {
            $timelimit = $time_from - $time_to;
        }

        $activesources = View::factory("parts/activesourceslargegraph")->set('json', $this->get_time_limit_json($timefrom, $timeto))->set('timelimit', $timelimit);

        // Render the graph
        $this->request->response = $activesources;
    }

    public function action_test()
    {
        // Get the graph - for the past 7 days
        $time_limit = 7;

        echo($this->get_day_limit_json($time_limit));
    }
}