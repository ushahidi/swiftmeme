<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_ActiveSources extends Controller
{
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

    public function action_getsources()
    {   
        $activesources = View::factory("parts/activesourceswidget");

        // Render the graph
        $this->request->response = $activesources;
    }

    public function action_getgraph()
    {
        // Get the graph - for the past 7 days
        $time_limit = 7;

        $activesources = View::factory("parts/activesourcesgraph")->set('json', $this->get_day_limit_json($time_limit))->set('timelimit', $time_limit);

        // Render the graph
        $this->request->response = $activesources;
    }

    public function action_getlargegraph()
    {
        $time_limit = 7;

        $activesources = View::factory("parts/activesourceslargegraph")->set('json', $this->get_day_limit_json($time_limit))->set('timelimit', $time_limit);

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