<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Parts_FacetGroup extends Controller_Template
{
    public $template = "/parts/facetgroup";

    public function action_render()
    {
        //TODO: Clean post variable
        $groups = $_POST["group"];
        $this->template->groups = $groups;
        $this->template->maxNameLength = 20;
    }
}