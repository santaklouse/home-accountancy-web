<?php defined('SYSPATH') or die('No direct script access.');

class ACL extends Base_ACL {

    public function allowed($core)
    {
        if (Auth::instance()->is_admin())
            return TRUE;

        return parent::allowed($core);
    }

}