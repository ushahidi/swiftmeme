<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_ActiveSources extends Controller
{
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

        $activesources_params = array("RequestType" => "SourcesByChannelOverTime", "Parameters" => array("TimeLimit" => $time_limit));
        $json_encoded_params = json_encode($activesources_params);
        $activesources_json = Analytics::analytics_api()->get_analysis($json_encoded_params);

        $activesources = View::factory("parts/activesourcesgraph")->set('json', $activesources_json)->set('timelimit', $time_limit);

        // Render the graph
        $this->request->response = $activesources;
    }

    public function action_getdayrangegraph($timelimit, $dayto)
    {
        $activesources_params = array("RequestType" => "SourcesByChannelOverTime", "Parameters" => array("TimeLimit" => $timelimit, "TimeTo" =>  $dayto));
        $json_encoded_params = json_encode($activesources_params);
        $activesources_json = Analytics::analytics_api()->get_analysis($json_encoded_params);

        $activesources = View::factory("parts/activesourcesgraph")->set('json', $activesources_json)->set('timelimit', $timelimit);

        // Render the graph
        $this->request->response = $activesources;
    }

    public function action_gettimerangegraph($timefrom, $timeto)
    {
        $activesources_params = array("RequestType" => "SourcesByChannelOverTime", "Parameters" => array("TimeRange" => "yes", "TimeFrom" => $timefrom, "TimeTo" =>  $timeto));
        $json_encoded_params = json_encode($activesources_params);
        $activesources_json = Analytics::analytics_api()->get_analysis($json_encoded_params);

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

        $activesources = View::factory("parts/activesourcesgraph")->set('json', $activesources_json)->set('timelimit', $timelimit);

        // Render the graph
        $this->request->response = $activesources;
    }

    public function action_test()
    {
        // Get the graph - for the past 7 days
        $time_limit = 7;

        $activesources_params = array("RequestType" => "SourcesByChannelOverTime", "Parameters" => array("TimeLimit" => $time_limit));
        $json_encoded_params = json_encode($activesources_params);
        $activesources_json = Analytics::analytics_api()->get_analysis($json_encoded_params);

        echo($activesources_json);
    }
}