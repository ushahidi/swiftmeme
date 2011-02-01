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