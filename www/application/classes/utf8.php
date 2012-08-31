<?php defined('SYSPATH') or die('No direct script access.');

class UTF8 extends Kohana_UTF8 {

    public static function ucfirst($str, $translate = TRUE)
    {
        if ($translate)
            return parent::ucfirst(__($str));
        return parent::ucfirst($str);
    }

}
