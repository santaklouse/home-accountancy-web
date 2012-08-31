<?php defined('SYSPATH') or die('No direct script access.');

class Helper_Language {

    public static function selector()
    {
        $all_languages = Model_Language::find_all()->records;
        $result = '';
        foreach ($all_languages as $language)
        {
            $href = URL::site('user_session/change_language/'.$language->name);
            $result .= HTML::anchor($href, '', array(
                'class' => 'change_language '.$language->name,
            ));
        }
        return $result;
    }
}