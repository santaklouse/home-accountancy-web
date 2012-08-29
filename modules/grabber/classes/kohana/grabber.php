<?php defined('SYSPATH') or die('No direct script access.');
/**
 * System for rendering pdf files using TCPDF library
 * in PHP 5, offering a flexible and elegant object-oriented approach to render
 * pdf with a multitude of features. 
 *
 * @package     Kohana/Grabber
 * @author      MadeIt Inc.
 * @copyright   (c) 2011 MadeIt Inc.
 * @license     
 */

class Kohana_Grabber
{

    protected $params = NULL;

    protected $keywords_grabber = NULL;

    public function __construct()
    {
        require_once Kohana::find_file('vendor', 'libs/keywords_grabber.class');
        $this->keywords_grabber = new cl_keywords_grabber();


    }
    
    public static function factory($config = 'default')
    {
       $class = new self();
       $class->params= Kohana::$config->load('grabber.'.$config);
       return $class;
    }
    
    public function grab_keywords($url, $params = array(),$assoc = FALSE)
    {
            $url_parts  = parse_url($url);
            if(!isset($url_parts['scheme']))
                $url = 'http://'.$url;

            $description_type = isset($params['options']['description']);
            $keywords_type  = isset($params['options']['body']);
            $meta_type = isset($params['options']['meta']);
            $params = Arr::merge($this->params,$params);

            $this->keywords_grabber->set_params($params);
            $results = $this->keywords_grabber->grabKeywords($url);

            if ($results['error'])
                return false;
            if(!$assoc)
                return array_keys($this->keywords_grabber->getGrabKeywords($assoc));

            $result_data = array();
            foreach ($this->keywords_grabber->getGrabKeywords($assoc) as $key => $value) 
            {
                if($key == 'meta' && $meta_type)
                {
                    $max = $params['options']['meta']['count'];
                    $result_data =array_merge($result_data,$this->__parse_grabbed_keywords($value,$max, TRUE, TRUE));
                }
                if($key == 'description' && $description_type)
                {
                    $max = (int)$params['options']['description']['count'];
                    $double = (bool) $params['options']['description']['types'][2];
                    $triple = (bool) $params['options']['description']['types'][3];
                    $result_data =array_merge($result_data,$this->__parse_grabbed_keywords($value,$max, $double,$triple));
                }
                if($key == 'body' && $keywords_type)
                {
                    $max = (int) $params['options']['body']['count'];
                    $double =  (bool) $params['options']['body']['types'][2];
                    $triple = (bool) $params['options']['body']['types'][3];
                    $result_data =array_merge($result_data,$this->__parse_grabbed_keywords($value,$max, $double,$triple));
                }
            }
            return $result_data;
    }
    
    private function __parse_grabbed_keywords($data,$max,$double,$triple)
    {
        $result = array();
        $data = array_slice($data,0,$max,TRUE);
        foreach ($data as $key => $value) 
        {
            $value = count(explode(" ",$key));
            if($value == 1)
                $result[] = $key;
            else if($value == 2 && $double)
                    $result[] = $key;
            else if($value == 3 && $triple)
                    $result[] = $key;
        }
        return $result;
    }
}