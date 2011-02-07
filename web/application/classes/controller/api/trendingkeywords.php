<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_TrendingKeywords extends Controller
{
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
    
    public function action_getkeywords()
    {
        $trendingkeywords = View::factory("parts/trendingkeywordswidget");
        // Render the graph
        $this->request->response = $trendingkeywords;
    }

    public function action_getgraph()
    {
        // Get the graph - for the past 7 days
        $time_limit = 7;

        $trendingkeywords = View::factory("parts/trendingkeywordsgraph")->set('json', $this->get_day_limit_json($time_limit))->set('timelimit', $time_limit);

        $this->request->response = $trendingkeywords;
    }

    public function action_getlargegraph()
    {
        // Get the graph - for the past 7 days
        $time_limit = 7;

        $trendingkeywords = View::factory("parts/trendingkeywordslargegraph")->set('json', $this->get_day_limit_json($time_limit))->set('timelimit', $time_limit);

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