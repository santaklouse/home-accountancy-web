<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Alerts extends Controller_Core {

    public $check_access = FALSE;

    public function action_date_format()
    {
        $this->render_partial();
    }

}