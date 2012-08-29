<?php

if (empty($base_docs))
{
    $base_docs = dirname(dirname(__FILE__)).'/';
}

require_once $base_docs . '/libs/url.class.php';
require_once $base_docs . '/libs/content_grabber.class.php';
require_once $base_docs . '/libs/reg_expressions.class.php';

class cl_spider
{
    protected $found_urls = array();
    protected $found_urls_count = 0;
    protected $level_urls = array();
    protected $new_urls = array();
    protected $processing_func = false;
    protected $processing_results = array();

    protected $params = array(
        // params for content grabber
        'grab_params' => array(
            'utf8' => true,
            'content_limit' => 1048576,
        ),
        // max grabbing level for spider
        'max_level' => 1,
        // max links count on page, separatelly for each internal and external links
        'max_page_links' => array(
            1 => 200,
            2 => 200,
            3 => 50,
            4 => 30,
            5 => 10,
        ),
        // max links count for certain level
        'max_level_links' => array(
            1 => 200,
            2 => 400,
            3 => 600,
            4 => 800,
            5 => 1000,
        ),
        // grab internal links
        'internal_links' => true,
        // grab external links
        'external_links' => true,
        // options for processing function
        'options' => array(),
        // memcache key for observing progress
        'memcache_key' => false,
    );

    public function set_params($params)
    {
        $this->params = $params + $this->params;
    }

    /**
    * Get a list of a keywords
    */
    public function grab_urls($base_url, $processing_func = false)
    {
        global $content_grabber,$language;
        if($content_grabber== NULL)
            $content_grabber = new cl_content_grabber();
        $this->processing_func = $processing_func;
        
        $this->found_urls = array();
        $this->found_urls_count = 0;
        $this->level_urls = array();
        $this->processing_results = array();

        $url_parts = parse_real_url($base_url);
        if ($url_parts === false)
        {
            return false;
        }
        $max_level_links = $this->params['max_level_links'];
        $memcache_key = $this->params['memcache_key'];

        $content_grabber->setGrabParams($this->params['grab_params']);

        $level = 1;
        $this->new_urls = $this->get_new_urls(array($base_url), $level, 'internal');
        $content_grabber->setOptions($this->new_urls);

        for(;;)
        {
            $this->new_urls = array();
            if ($memcache_key)
            {
                $content_grabber->enableMemcache(true, $memcache_key, array(
                    'title' => 'level'.' '.$level,
                    'per' => 1,
                ));
            }
            $content_grabber->grabContent(array($this, 'analyze_response'));
            if (empty($this->new_urls) || $level >= $this->params['max_level'])
            {
                break;
            }
            $max_links = isset($max_level_links[$level]) ? $max_level_links[$level] : false;
            if ($max_links === false)
            {
                $max_links = end($max_level_links);
            }

            $url_chunks = array_chunk($this->new_urls, $max_links, true);
            $this->new_urls = $url_chunks[0];
            unset($url_chunks);

            $content_grabber->setOptions($this->new_urls);
            ++$level;
        }
        $this->found_urls_count = count($this->found_urls);
        $this->found_urls = array();
        return true; 
    }

    public function get_level_urls()
    {
        return $this->level_urls;
    }

    public function get_found_urls($internal = true, $external = true)
    {
        $result = array();
        foreach ($this->level_urls as $level => &$places)
        {
            if ($internal && isset($places['internal']))
            {
                $result += $places['internal'];
            }
            if ($external && isset($places['external']))
            {
                $result += $places['external'];
            }
        }
        return array_keys($result);
    }
    
    public function get_found_urls_count()
    {
        return $this->found_urls_count;
    }
    
    public function get_processing_results()
    {
        return $this->processing_results;
    }

    protected function get_new_urls($link_urls, $level, $place)
    {
        $new_urls = array();

        $max_page_links = $this->params['max_page_links'];
        $max_links = isset($max_page_links[$level]) ? $max_page_links[$level] : false;
        if ($max_links === false)
        {
            $max_links = end($max_page_links);
        }

        foreach($link_urls as $link_url)
        {
            if (!isset($this->found_urls[$link_url]))
            {
                $this->found_urls[$link_url] = false;
                $this->level_urls[$level][$place][$link_url] = false;
                if (count($new_urls) < $max_links)
                {
                    $new_urls[$link_url] = array(
                        'level' => $level,
                        'url' => $link_url,
                        'followlocation' => true,
                        'place' => $place,
                    );
                }
            }
        }
        return $new_urls;
    }

    public function analyze_response($response)
    {
        global $reg_expressions;
        if($reg_expressions == NULL)
            $reg_expressions = new cl_reg_expressions();

        $url = isset($response['options']['url']) ? $response['options']['url'] : false;
        if ($url)
        {
            $level = isset($response['options']['level']) ? $response['options']['level'] : false;
            $place = isset($response['options']['place']) ? $response['options']['place'] : false;
            if ($response['success'] && $level && $place)
            {
                $this->found_urls[$url] = true;
                $this->level_urls[$level][$place][$url] = true;

                if ($this->processing_func)
                {
                    $this->processing_results[$url] = call_user_func_array($this->processing_func, array(&$response, $this->params['options']));
                }

                if ($level < $this->params['max_level'])
                {
                    $parse_links = $reg_expressions->parse_tag_urls($response['content'], 'a', array('href'), $url);
                    if ($this->params['internal_links'])
                    {
                        $this->new_urls += $this->get_new_urls($parse_links['internal'], $level + 1, 'internal');
                    }
                    if ($this->params['external_links'])
                    {
                        $this->new_urls += $this->get_new_urls($parse_links['external'], $level + 1, 'external');
                    }
                }
                return true;
            }
        }
        return false;
    }
}

$spider = new cl_spider();
/*
set_time_limit(300);

echo '<pre>';
function processing_func(&$response, $options)
{
    return '('.$options['my_option'].')';
}
$spider->set_params(array(
    'max_level' => 2,
    'options' => array('my_option' => 1),
    'grab_params' => array(
        'utf8' => true,
        'content_limit' => 1048576,
        'group_count' => 20,
        'group_delay' => 1,
    ),
));
if ($spider->grab_urls('http://podrobnosti.ua/', 'processing_func'))
{
    print_r($spider->get_level_urls());
    print_r($spider->get_found_urls(true, true));
}
echo '</pre>';
*/