<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_FilterView extends Controller
{
    public function action_getfilterpost()
    {
        // Get the data
        $json_encoded_parameters = json_encode($_POST['postdata']);
        $widget = View::factory("parts/filterviewwidget")->set('json', array("json" => $json_encoded_parameters, "tag_count" => 20));

        // Render the widget
        $this->request->response = $widget;
    }
}