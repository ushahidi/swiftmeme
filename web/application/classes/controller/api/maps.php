<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Maps extends Controller
{
    public $widgetid = '';

    public function action_getmap()
    {
        $map = new View("parts/mapwidget");

        // Render the graph
        $this->request->response = $map;
    }
}