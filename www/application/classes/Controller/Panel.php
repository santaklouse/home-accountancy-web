<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel extends Controller_Core {

    public function action_index()
    {
        $this->register_media('calendar');
        $this->register_media('jquery/jquery.tipTip');
    }

}