<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_ActiveSources extends Controller
{
    public function action_getsources()
    {
        // Get the graph
        $activesources = new View("parts/activesourceswidget");

        // Render the graph
        $this->request->response = $activesources;
    }
}