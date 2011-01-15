<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Maps extends Controller
{
    public $widgetid = '';

    public function action_getmap($state, $minVeracity, $maxVeracity, $type, $subType, $source, $pageSize, $pageStart, $orderBy)
    {
        // Content data
        $params = array();

        if($state != null && $state != "null")                  $params["state"] = $state;
        if($minVeracity != null && $minVeracity != "null")      $params["minVeracity"] = (int)$minVeracity;
        if($maxVeracity != null && $maxVeracity != "null")      $params["maxVeracity"] = (int)$maxVeracity;
        if($type != null && $type != "null")                    $params["type"] = $type;
        if($subType != null && $subType != "null")              $params["subType"] = $subType;
        if($source != null && $source != "null")                $params["source"] = $source;
        if($pageSize != null && $pageSize != "null")            $params["pageSize"] = (int)$pageSize;
        if($pageStart != null && $pageStart != "null")          $params["pageStart"] = (int)$pageStart;
        if($orderBy != null && $orderBy != "null")              $params["orderBy"] = $orderBy;

        $json_encoded_parameters = json_encode($params);

        // You enable this if you want to work with content instead of sources
        // $content_json = API::content_api()->get_content_list($json_encoded_parameters);

        // Source data
        $source_json = API::sources_api()->get_all_sources();

        // Use the sources data for now
        $map = View::factory("parts/mapwidget")->set('json', $source_json);

        // Render the graph
        $this->request->response = $map;
    }
}