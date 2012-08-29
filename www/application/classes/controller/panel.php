<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Panel extends Controller_Core {

    public function action_index()
    {
        $this->register_js_file(array(
            'name' => 'libs',
            'files' => array(
                'lib/loading_icon',
                'lib/live_dialog',
                'lib/pseudo_dialog',

            ),
        ));
        $this->register_media('calendar');
        $this->register_media('jquery/jquery.tipTip');
    }

}