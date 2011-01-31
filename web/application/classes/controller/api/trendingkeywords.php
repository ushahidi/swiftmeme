<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_TrendingKeywords extends Controller
{
    public function action_getkeywords()
    {
        $trendingkeywords = View::factory("parts/trendingkeywordswidget");
        // Render the graph
        $this->request->response = $trendingkeywords;
    }

    public function action_getgraph()
    {
        // Get the graph - for the past 7 days
        $trendingkeywords_params = array("RequestType" => "SourcesByChannelOverTime", "Parameters" => array("TimeLimit" => 7));
        $json_encoded_params = json_encode($trendingkeywords_params);
        $trendingkeywords_json = Analytics::analytics_api()->get_analysis($json_encoded_params);

        $trendingkeywords = View::factory("parts/trendingkeywordsgraph")->set('json', $trendingkeywords_json);

        $this->request->response = $trendingkeywords;
    }
}