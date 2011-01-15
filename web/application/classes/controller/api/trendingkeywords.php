<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_TrendingKeywords extends Controller
{
    public function action_getkeywords()
    {
        // Get the graph - for the past 7 days
        $trendingkeywords_params = array("RequestType" => "ContentByChannelOverTime", "Parameters" => array("TimeLimit" => 7));
        $json_encoded_params = json_encode($trendingkeywords_params);
        $trendingkeywords_json = Analytics::analytics_api()->get_analysis($json_encoded_params);
        
        $trendingkeywords = View::factory("parts/trendingkeywordswidget")->set('json', $trendingkeywords_json);

        // Render the graph
        $this->request->response = $trendingkeywords;
    }
}