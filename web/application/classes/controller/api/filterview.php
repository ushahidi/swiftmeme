<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_FilterView extends Controller
{
    public function action_getfilterpost()
    {
        // Get the data
        $json_encoded_parameters = json_encode($_POST['postdata']);
        $json = API::content_api()->get_content_list($json_encoded_parameters);
        $widget = View::factory("parts/filterviewwidget")->set('json', $json);

        // Render the widget
        $this->request->response = $widget;
    }
}