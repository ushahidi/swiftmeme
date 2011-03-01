<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_TrendingKeywords extends Controller
{
    // Fetching and processing data
    private function get_day_limit_json($time_limit, $day_to = 0)
    {
        $trendingkeywords_params = null;

        if($day_to > 0) {
            $trendingkeywords_params = array("RequestType" => "ContentByChannelOverTime", "Parameters" => array("TimeLimit" => $time_limit, "TimeTo" => $day_to));
        }
        else {
            $trendingkeywords_params = array("RequestType" => "ContentByChannelOverTime", "Parameters" => array("TimeLimit" => $time_limit));
        }


        $json_encoded_params = json_encode($trendingkeywords_params);
        $trendingkeywords_json = Analytics::analytics_api()->get_analysis($json_encoded_params);

        return $trendingkeywords_json;
    }

    private function get_time_limit_json($time_from, $time_to)
    {
        $trendingkeywords_params = array("RequestType" => "ContentByChannelOverTime", "Parameters" => array("TimeRange" => "yes", "TimeFrom" => $time_from, "TimeTo" => $time_to));
        $json_encoded_params = json_encode($trendingkeywords_params);
        $trendingkeywords_json = Analytics::analytics_api()->get_analysis($json_encoded_params);

        return $trendingkeywords_json;
    }

    private function process_json($json, $timelimit)
    {
        $json_decoded = json_decode($json);
        $json_data = $json_decoded->data;
        $sample_channel_name = "";

        // Channel data
        $channel_data = array();
        // Day data
        $days_data = array();
        // Flows to the next month
        $flows_to_next_month = false;

        // Statistical ranges
        $peak_statistic = 0;
        $min_day_range = 0;
        $max_day_range = 0;

        $data_exists = false;

        if(is_null($json_data) || $json_data == "null") {
            // There should not be anything else to display
            $data_exists = false;
        }
        else {
            // Process the content items
            if(count($json_data) == 0) {
                $data_exists = false;
            }
            else {
                $data_exists = true;
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

                $last_new_month_day =  $current_date;
                $first_old_month_day = $max_day_range - ($timelimit - $last_new_month_day);

                if(($last_new_month_day - count($days_data)) < 0) {
                    $flows_to_next_month = true;
                    $min_day_range = $first_old_month_day + 1;
                }
            }
        }

        return array("data_exists" => $data_exists,
            "channel_data" => $channel_data,
            "days_data" => $days_data,
            "flows_to_next_month" => $flows_to_next_month,
            "min_day_range" => $min_day_range,
            "max_day_range" => $max_day_range,
            "peak_statistic" => $peak_statistic,
            "first_old_month_day" => $first_old_month_day,
            "last_new_month_day" => $last_new_month_day);
    }

    // Actions
    
    public function action_getkeywords()
    {
        $time_limit = 7;

        $trendingkeywords = View::factory("parts/trendingkeywordswidget")->set('params', $this->process_json($this->get_day_limit_json($time_limit), $time_limit));
        
        // Render the graph
        $this->request->response = $trendingkeywords;
    }

    public function action_getdayrangegraph($timelimit, $dayto)
    {
        $trendingkeywords = View::factory("parts/trendingkeywordsgraph")->set('json', $this->get_day_limit_json($timelimit, $dayto))->set('timelimit', $timelimit);

        $this->request->response = $trendingkeywords;
    }

    public function action_gettimerangegraph($timefrom, $timeto)
    {   
        $time_from = intval(date('z', $timefrom));
        $time_to = intval(date('z', $timeto));

        $timelimit = 0;

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

        $trendingkeywords = View::factory("parts/trendingkeywordsgraph")->set('json', $this->get_time_limit_json($timefrom, $timeto))->set('timelimit', $timelimit);

        $this->request->response = $trendingkeywords;
    }

    public function action_test()
    {
        // Get the graph - for the past 7 days
        $time_limit = 7;

        echo($this->get_day_limit_json($time_limit));
    }
}