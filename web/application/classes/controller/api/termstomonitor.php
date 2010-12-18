<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api_TermsToMonitor extends Controller
{
    public function action_getterms($withcontrols)
    {
        $json = API::channel_api()->get_all_channels();
        $widget = View::factory("parts/termstomonitorwidget")->set('json', $json);

        $loggedinstatus = RiverId::is_logged_in();
        $loggedinstatus = $loggedinstatus["IsLoggedIn"];

        if($withcontrols == "true") {
            $widget->set('withcontrols', true);
        }
        else {
            $widget->set('withcontrols', false);
        }

        $widget->set('isloggedin',  $loggedinstatus);

        // Render the widget
        $this->request->response = $widget;
    }
}