<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_ActiveSources extends Controller
{
    public function action_getsources()
    {
        // Get the graph - for the past 7 days
        $activesources_params = array("RequestType" => "SourcesByChannelOverTime", "Parameters" => array("TimeLimit" => 7));
        $json_encoded_params = json_encode($activesources_params);
        $activesources_json = Analytics::analytics_api()->get_analysis($json_encoded_params);
        
        $activesources = View::factory("parts/activesourceswidget")->set('json', $activesources_json);

        // Render the graph
        $this->request->response = $activesources;
    }
}