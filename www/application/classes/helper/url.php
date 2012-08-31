<?php defined('SYSPATH') or die('No direct script access.');

class Helper_Url {

    public static function current_url($request, $url)
    {
        $current_path = array(
            $request->directory(),
            $request->controller(),
            $request->action()
        );
        if ($request->controller() == 'welcome' && $request->action() == 'index')
            $current_path = array();
        if (isset($current_path[0]) && ! Arr::get($current_path, 0))
        {
            unset($current_path[0]);
        }
        $current_path = implode('/', $current_path);

        if ($url && count(explode('/', $url)) == 1)
        {
            $url .= '/index';
        }
        if ($url == '/index')
        {
            $url = 'welcome/index';
        }
        return $current_path == $url;
    }

}