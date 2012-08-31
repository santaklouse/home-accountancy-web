<?php defined('SYSPATH') or die('No direct script access.');

class Language
{
    // language is the Model_Language object
    public static function set($language)
    {
        Session::instance()->set('language', $language);
    }

    public static function get()
    {
        $language = Session::instance()->get('language');
        return $language ?: self::get_default();
    }

    public static function get_default()
    {
        $language = Session::instance()->get('default_language');
        if ($language)
            return $language;

        $language = Model_Language::find(array(
            'name' => Kohana::$config->load('site.default_language')
        ));
        Session::instance()->set('default_language', $language);
        return $language;
    }

    public static function expected_language_id()
    {
        $expected_language = '';
        $user_languages = array_keys(Request::accept_lang());
        if ( ! empty($user_languages) && isset($user_languages[0]))
        {
            $expected_language = strtolower(substr($user_languages[0], 0, 2));
        }
        $system_languages = Model_Language::find_all()->records;
        $language_names = Model::collection_to_hash($system_languages, 'id', 'name');
        $default_language_id = array_search('en', $language_names);

        return array_search($expected_language, $language_names)
            ?: $default_language_id;
    }

    //may be return incorrect information (for tests)
    public static function get_country_by_ip($ip_address)
    {
        $ip_detail = array();
        $f = file_get_contents("http://api.hostip.info/?ip=" . $ip_address);

        //get city name
        preg_match("@<Hostip>(\s)*<gml:name>(.*?)</gml:name>@si", $f, $city);
        $ip_detail['city'] = $city[2];

        //get country name
        preg_match("@<countryName>(.*?)</countryName>@si", $f, $country);
        $ip_detail['country'] = $country[1];

        //get country code
        preg_match("@<countryAbbrev>(.*?)</countryAbbrev>@si", $f, $countryCode);
        $ip_detail['countryCode'] = $countryCode[1];

        return $ip_detail;
    }

    //ver. 1
    public static function expected_country_id($language_id)
    {
        $language_full_names = Model::collection_to_hash(
            Model_Language::find_all()->records,
            'id',
            'full_name'
        );

        $countries = Model::collection_to_hash(
            Model_Country::find_all()->records,
            'id',
            'name'
        );
        $language_full_name = strtolower($language_full_names[$language_id]);
        $similarity = array();
        foreach ($countries as $id => $name)
        {
            $name = strtolower($name);
            $first = (strlen($name) > strlen($language_full_name))
                ? $name
                : $language_full_name;
            $second = ($first == $name)
                ? $language_full_name
                : $name;
            similar_text($first, $second, $similarity[$id]);
        }
        arsort($similarity);
        $keys = array_keys($similarity);
        $expected_country_id = reset($keys);
        if ($language_full_name == 'english')
        {
            $expected_country_id = array_search('USA', $countries);
        }
        return $expected_country_id;
    }
}
