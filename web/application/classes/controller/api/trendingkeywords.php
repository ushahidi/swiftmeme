<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_TrendingKeywords extends Controller
{
    public function action_getkeywords()
    {
        // Get the graph
        $trendingkeywords = new View("parts/trendingkeywordswidget");

        // Render the graph
        $this->request->response = $trendingkeywords;
    }
}