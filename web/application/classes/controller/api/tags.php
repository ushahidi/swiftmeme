<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_Tags extends Controller
{
    public function action_getcommontags()
    {
        $num_items = 20;

        $json = API::content_api()->get_common_tags($num_items);
        $this->request->response = $json;
    }
}