<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_ContentCuration extends Controller
{
    public function action_markasaccurate($contentId)
    {
        $json_encoded_parameters = json_encode(array(
            "id" => $contentId,
            "markerId" => "swiftriver_dev_marker",
        ));
        $json = API::content_api()->mark_content_as_accurate($json_encoded_parameters);

        $this->request->response = $json;
    }

    public function action_markasinaccurate($contentId)
    {
        $json_encoded_parameters = json_encode(array(
            "id" => $contentId,
            "markerId" => "swiftriver_dev_marker",
            "reason" => "falsehood"
        ));
        $json = API::content_api()->mark_content_as_inaccurate($json_encoded_parameters);

        $this->request->response = $json;
    }

    public function action_markasirrelevant($contentId)
    {
        $json_encoded_parameters = json_encode(array(
            "id" => $contentId,
            "markerId" => "swiftriver_dev_marker"
        ));
        $json = API::content_api()->mark_content_as_irrelevant($json_encoded_parameters);

        $this->request->response = $json;
    }

    public function action_markascrosstalk($contentId)
    {
        $json_encoded_parameters = json_encode(array(
            "id" => $contentId,
            "markerId" => "swiftriver_dev_marker",
        ));
        $json = API::content_api()->mark_content_as_cross_talk($json_encoded_parameters);

        $this->request->response = $json;
    }
}
