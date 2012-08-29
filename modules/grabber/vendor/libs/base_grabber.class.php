<?php

if (empty($base_docs))
{
    $base_docs = dirname(dirname(__FILE__)).'/';
}
require_once $base_docs . '/libs/url.class.php';
require_once $base_docs . '/libs/reg_expressions.class.php';

class cl_base_grabber
{
    protected $options = array();
    protected $defaultOptions = array(
            'url' => array(CURLOPT_URL, ''),
            'useragent' => array(CURLOPT_USERAGENT, '*', true), // use random by default
            'httpheader' => array(CURLOPT_HTTPHEADER, array(
                    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                    "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7",
                    //"Accept-Encoding: gzip, deflate",
                    "Accept-Language: en-us,en;q=0.5",
                    "Cache-Control: max-age=0",
                    "Connection: keep-alive",
                    "Keep-Alive: 300",
                    "Pragma: no-cache",
                )),
            'referer' => array(CURLOPT_REFERER, '*', true), // use random by default
            'encoding' => array(CURLOPT_ENCODING, 'gzip,deflate'),
            'followlocation' => array(CURLOPT_FOLLOWLOCATION, false),
            'autoreferer' => array(CURLOPT_AUTOREFERER, true),
            'returntransfer' => array(CURLOPT_RETURNTRANSFER, true),
            'failonerror' => array(CURLOPT_FAILONERROR, true),
            'timeout' => array(CURLOPT_TIMEOUT, 10),
            'cookie' => array(CURLOPT_COOKIE, null),
            'proxy' => array(CURLOPT_PROXY, null),
            'post' => array(CURLOPT_POST, null),
            'postfields' => array(CURLOPT_POSTFIELDS, null),
        );
    protected $defaultValues = array(
        'useragent' => array(
            'Opera/9.63 (Windows NT 6.0; U; ru) Presto/2.1.1',
            'Opera/9.80 (X11; Linux i686; U; ru) Presto/2.6.30 Version/10.61',
            'Opera/9.80 (Windows NT 6.1; U; it) Presto/2.6.30 Version/10.63',
            'Opera/9.80 (Windows NT 5.1; U; en) Presto/2.6.37 Version/11.00',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1) Gecko/20090624 Firefox/3.5',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/534.6 (KHTML, like Gecko) Chrome/7.0.503.0 Safari/534.6',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9.2.11) Gecko/20101012 Firefox/3.6.11',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; it; rv:1.9.0.16) Gecko/2010021013 Firefox/3.0.16 Flock/2.5.6',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/534.7 (KHTML, like Gecko) Chrome/7.0.517.41 Safari/534.7',
            'Mozilla/5.0 (Windows; U; Windows NT 5.1; cs; rv:1.9.1.14) Gecko/20101001 Firefox/3.5.14',
            'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.7.13) Gecko/20050610 K-Meleon/0.9',
            'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.2.10) Gecko/20100914 Firefox/3.6.10',
            'Mozilla/5.0 (Windows; U; Windows NT 6.1; de; rv:1.9.2.11) Gecko/20101012 AskTbSPC2/3.8.0.12304 Firefox/3.6.11',
            'Mozilla/5.0 (Windows; U; Windows NT 6.1; de-DE) AppleWebKit/533.18.1 (KHTML, like Gecko) Version/5.0.2 Safari/533.18.5',
            'Mozilla/5.0 (Windows NT 5.1; U; en; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6',
            'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; WOW64; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; Media Center PC 5.0)',
            'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0)',
            'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_6; en-us) AppleWebKit/525.27.1 (KHTML, like Gecko) Version/3.2.1 Safari/525.27.1',
            'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.2) Gecko/20070221 SeaMonkey/1.1.1',
            'Mozilla/5.0 (X11; U; Linux i686 (x86_64); en-US; rv:1.8.1.6) Gecko/2007072300 Iceweasel/2.0.0.6 (Debian-2.0.0.6-0etch1+lenny1)',
            'Mozilla/5.0 (X11; U; Linux x86_64; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.11 Safari/534.10',
            'Mozilla/5.0 (X11; U; Linux x86_64; de; rv:1.9.2.11) Gecko/20101013 Ubuntu/10.10 (maverick) Firefox/3.6.11',
            'Mozilla/5.0 (X11; U; Linux i686; it; rv:1.9.2.10) Gecko/20100915 Ubuntu/10.04 (lucid) Firefox/3.6.10 GTB7.1',
            'Mozilla/5.0 (X11; U; Linux i686; it-it) AppleWebKit/531.2+ (KHTML, like Gecko) Safari/531.2+ Epiphany/2.30.6',
            'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.11) Gecko/20101013 Ubuntu/10.04 (lucid) Firefox/3.6.11',
            'Mozilla/5.0 (compatible; Konqueror/3.5; Linux) KHTML/3.5.5 (like Gecko) (Debian|Debian)',
            'Mozilla/5.0 (Linux; U; Android 2.1-update1; ru-ru; GT-I9000 Build/ECLAIR) AppleWebKit/530.17 (KHTML, like Gecko) Version/4.0 Mobile Safari/530.17',
            'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_4; en-us) AppleWebKit/533.18.1 (KHTML, like Gecko) Version/5.0.2 Safari/533.18.5',
            'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; it; rv:1.9.2.11) Gecko/20101012 Firefox/3.6.11',
            'Lynx/2.8.6rel.4 libwww-FM/2.14 SSL-MM/1.4.1 OpenSSL/0.9.8g',
        ),
        'referer' => array(
            'http://www.google.com/',
            'http://www.bing.com/',
            'http://www.yahoo.com/',
            'http://www.yandex.com/',
            'http://www.goby.com/',
            'http://duckduckgo.com/',
            'http://www.viewzi.com/',
            'http://www.blackle.com/',
            'http://www.sproose.com/',
            'http://www.wikiseek.com/',
        ),
        'proxy' => array(
        ),
    );
    protected $grabData = array();
    protected $params = array();

    // Set options
    
    public function __construct()
    {
        $this->restoreParams();
    }
    
    public function restoreParams()
    {
        $this->params = array(
            'content_types' => array('text/html', 'text/css', 'text/plain', 'application/javascript', 'application/x-javascript'),
            'content_limit' => false,
            'utf8' => false,
            'auto_group' => false,
            'group_count' => null,
            'group_delay' => 0,
        );
    }

    public function setOptions($options)
    {
        if (is_array($options))
        {
            reset($options);
            if (is_array(current($options)))
            {
                $this->options = $options;
            }
            else
            {
                $this->options = array($options);
            }
        }
    }

    public function setDefaultOptions($option, $values = array())
    {
        $this->defaultValues[$option] = $values;
    }
    
    public function getDefaultValue($option)
    {
        if (!isset($this->defaultValues[$option]))
        {
            return null;
        }
        $values = $this->defaultValues[$option];
        $key = array_rand($values);
        return $values[$key];
    }

    public function setGrabParams($params = array())
    {
        $this->params = $params + $this->params;
    }

    public function getGrabParam($param)
    {
        if (!isset($this->params[$param]))
        {
            return null;
        }
        return $this->params[$param];
    }

    // Memcache functions
    protected function updateMemcache(&$data) { }
    protected function releaseMemcache() { }

    // Functions return results immediately in custom functions

    protected function createCurlHandler($options)
    {
        $handle = curl_init();
        if ($handle !== false)
        {
            foreach ($this->defaultOptions as $key => $option)
            {
                $value = isset($options[$key]) ? $options[$key] : $option[1];
                if (isset($this->defaultOptions[$key][2]) && $value == '*')
                {
                    $rand_index = mt_rand(0, count($this->defaultValues[$key]) - 1);
                    $value = $this->defaultValues[$key][$rand_index];
                }
                if ($value === null)
                {
                    continue;
                }
                $open_basedir = ini_get('open_basedir');
                $safe_mode = ini_get('safe_mode');
                if ($key == 'followlocation' && (!empty($open_basedir) || (!empty($safe_mode) && strcasecmp($safe_mode, 'Off') != 0)))
                {
                    continue;
                }
                curl_setopt($handle, $option[0], $value);
            }
        }
        return $handle;
    }
    
    protected function contentPostProcessing(&$content, $content_type)
    {
        global $reg_expressions;
        if($reg_expressions == NULL)
            $reg_expressions = new cl_reg_expressions();

        $result = array();
        if ($content !== false)
        {
            if ($this->params['content_limit'] && strlen($content) > $this->params['content_limit'])
            {
                $content = substr($content, 0, $this->params['content_limit']);
            }
            if ($this->params['utf8'])
            {
                $charset = $reg_expressions->encode_from_content_type($content, $content_type);
                if (!$charset)
                {
                    $charset = $reg_expressions->convert_encoding($content);
                }
                $result['charset'] = $charset;
            }
        }
        return $result;
    }

    public function grabSerial($processing_func)
    {
        $total = count($this->options);
        $number = 0;
        if ($total)
        {
            $data = array(
                'number' => $number,
                'total' => $total,
                'runtime' => 0,
            );
            $this->updateMemcache($data);

            $groups = array();
            if ($this->params['auto_group'])
            {
                $groups = $this->groupOptions($this->options);
            }
            else if ($this->params['group_count'])
            {
                $groups = $this->groupOptions($this->options, $this->params['group_count']);
            }
            else
            {
                $groups = array(array_keys($this->options));
            }
            $group_delay = $this->params['group_delay'];

            $is_not_first = false;
            foreach ($groups as $group_keys)
            {
                if ($group_delay && $is_not_first)
                {
                    $delay = $group_delay;
                    if (is_array($group_delay))
                    {
                        $delay = mt_rand($group_delay[0], $group_delay[1]);
                    }
                    sleep($delay);
                }
                $is_not_first = true;

                foreach ($group_keys as $key)
                {
                    $time_start = microtime(true);
                    
                    $content = false;
                    $http_code = 0;
                    $error = false;
                    $content_type = false;
                    $extra_info = array();

                    $handle = $this->createCurlHandler($this->options[$key]);
                    $accept_content_types = isset($this->options[$key]['content_types']) ? $this->options[$key]['content_types'] : $this->params['content_types'];
                    if ($handle && !empty($accept_content_types))
                    {
                        curl_setopt($handle, CURLOPT_NOBODY, true);
                        $content = curl_exec($handle);
                        if ($content !== false)
                        {
                            $content_type = curl_getinfo($handle, CURLINFO_CONTENT_TYPE);
                            curl_close($handle);

                            $handle = $this->createCurlHandler($this->options[$key]);
                        }
                    }
                    if ($handle)
                    {
                        if ($content_type)
                        {
                            if (!preg_match('#^('.implode('|', $accept_content_types).')#si', $content_type))
                            {
                                $error = 'Unsupported content type: '.$content_type;
                            }
                        }
                        if (!$error)
                        {
                            $content = curl_exec($handle);
                            $extra_info = $this->contentPostProcessing($content, $content_type);
                        }

                        if ($content !== false)
                        {
                            $http_code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
                        }
                        if (!$error && ($content === false || $http_code == 0))
                        {
                            $error = curl_error($handle);
                        }
                        $data = array(
                            'number' => ++$number,
                            'total' => $total,
                            'options' => $this->options[$key],
                            'content' => $content,
                            'http_code' => $http_code,
                            'runtime' => microtime(true) - $time_start,
                            'error' => $error,
                            'success' => ($error === false && $http_code == 200),
                            'content_type' => $content_type,
                        ) + $extra_info;
                        $this->updateMemcache($data);
                        curl_close($handle);
                        call_user_func($processing_func, $data);
                    }
                }
            }
            $this->releaseMemcache();
        }
        return $number;
    }

    public function grabMulti($processing_func)
    {
        $total = count($this->options);
        $number = 0;
        if ($total)
        {
            $data = array(
                'number' => $number,
                'total' => $total,
                'runtime' => 0,
            );
            $this->updateMemcache($data);
            $time_start = microtime(true);

            $groups = array();
            if ($this->params['auto_group'])
            {
                $groups = $this->groupOptions($this->options);
            }
            else if ($this->params['group_count'])
            {
                $groups = $this->groupOptions($this->options, $this->params['group_count']);
            }
            else
            {
                $groups = array(array_keys($this->options));
            }
            $group_delay = $this->params['group_delay'];

            $is_not_first = false;
            foreach ($groups as $group_keys)
            {
                if ($group_delay && $is_not_first)
                {
                    $delay = $group_delay;
                    if (is_array($group_delay))
                    {
                        $delay = mt_rand($group_delay[0], $group_delay[1]);
                    }
                    sleep($delay);
                }
                $is_not_first = true;

                $curls = array();
                foreach ($group_keys as $key)
                {
                    $handle = $this->createCurlHandler($this->options[$key]);
                    if ($handle)
                    {
                        $curls[(int)$handle] = array('key' => $key, 'handle' => $handle);
                    }
                }

                $multi_handle = curl_multi_init();
                foreach ($curls as $info)
                {
                    curl_multi_add_handle($multi_handle, $info['handle']);
                }

                $running = null;
                do
                {
                    while (($multi_result = curl_multi_exec($multi_handle, $running)) === CURLM_CALL_MULTI_PERFORM);
                    if ($multi_result !== CURLM_OK)
                    {
                        break;
                    }

                    while ($info = curl_multi_info_read($multi_handle))
                    {
                        $handle = $info['handle'];
                        $key = isset($curls[(int)$handle]) ? $curls[(int)$handle]['key'] : null;
                        $content = false;
                        $http_code = 0;
                        $error = false;
                        $extra_info = array();

                        $content_type = curl_getinfo($handle, CURLINFO_CONTENT_TYPE);

                        $accept_content_types = isset($this->options[$key]['content_types']) ? $this->options[$key]['content_types'] : $this->params['content_types'];
                        if (!empty($accept_content_types) && $content_type)
                        {
                            if (!preg_match('#^('.implode('|', $accept_content_types).')#si', $content_type))
                            {
                                $error = 'Unsupported content type: '.$content_type;
                            }
                        }
                        if (!$error)
                        {
                            $content = curl_multi_getcontent($handle);
                            $extra_info = $this->contentPostProcessing($content, $content_type);
                        }
                        if ($content !== false)
                        {
                            $http_code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
                        }
                        if (!$error && ($content === false || $http_code == 0))
                        {
                            $error = curl_error($handle);
                        }
                        $data = array(
                            'number' => ++$number,
                            'total' => $total,
                            'options' => isset($this->options[$key]) ? $this->options[$key] : array(),
                            'content' => $content,
                            'http_code' => $http_code,
                            'runtime' => microtime(true) - $time_start,
                            'error' => $error,
                            'success' => ($error === false && $http_code == 200),
                            'content_type' => $content_type,
                        ) + $extra_info;
                        $this->updateMemcache($data);
                        curl_multi_remove_handle($multi_handle, $handle);
                        call_user_func($processing_func, $data);
                    }
                } while($running);
                curl_multi_close($multi_handle);
            }
            $this->releaseMemcache();
        }
        return $number;
    }
    
    public function grabContent($processing_func)
    {
        $options_count = count($this->options);
        if ($options_count == 0)
        {
            return 0;
        }
        if ($options_count == 1)
        {
            return $this->grabSerial($processing_func);
        }
        return $this->grabMulti($processing_func);
    }
    
    protected function groupOptions(&$options, $group_count = null)
    {
        $result = array();
        if ($group_count === null)
        {
            $groups = array();
            foreach ($options as $key => $option)
            {
                if (!empty($option['group_label']))
                {
                    $groups[$option['group_label']][] = $key;
                }
                else if (!empty($option['url']))
                {
                    $domain = get_safe_domain($option['url']);
                    $groups[$domain][] = $key;
                }
            }
            foreach ($groups as $domain => $group)
            {
                $i = 0;
                foreach ($group as $key)
                {
                    $result[$i][] = $key;
                    ++$i;
                }
            }
        }
        else
        {
            $i = 0; $j = 0;
            foreach ($options as $key => $option)
            {
                if ($j == $group_count)
                {
                    ++$i; $j = 0;
                }
                $result[$i][] = $key;
                ++$j;
            }
        }
        return $result;
    }

    // Functions return results after processing

    protected function defaultProcessing(&$data)
    {
        $this->grabData[] = $data;
    }

    public function getGrabData()
    {
        return $this->grabData;
    }

    public function getSerialContent($options = array())
    {
        $this->setOptions($options);
        $this->grabData = array();
        if ($this->grabSerial(array($this, 'defaultProcessing')))
        {
            return $this->grabData;
        }
        return false;
    }

    public function getMultiContent($options = array())
    {
        $this->setOptions($options);
        $this->grabData = array();
        if ($this->grabMulti(array($this, 'defaultProcessing')))
        {
            return $this->grabData;
        }
        return false;
    }
    
    public function getError($index = -1)
    {
        $grab_count = count($this->grabData);
        if ($grab_count)
        {
            if ($index < 0)
            {
                $index += $grab_count; 
            }
            if (isset($this->grabData[$index]))
            {
                return $this->grabData[$index]['error'];
            }
        }
        return '';
    }
}

$base_grabber = new cl_base_grabber();
/*
echo '<pre>';
function analyze_page(&$data)
{
    echo htmlentities(print_r($data, true));
}
$base_grabber->setGrabParams(array(
    //'content_limit' => 100,
    'auto_group' => true,
    //'group_count' => 2,
    'group_delay' => 1,
));
$base_grabber->setOptions(array(
//  array('key' => 'php', 'url' => 'http://www.php.net/'),
//  array('key' => 'ico', 'url' => 'http://www.allwebsuite.com/favicon.ico'),
//  array('key' => 'css', 'url' => 'http://devzone.zend.com/css/screen.css'),
//  array('key' => 'javascript', 'url' => 'http://devzone.zend.com/js/common.js'),
//  array('key' => 'google', 'url' => 'http://www.google.com/'),
//  array('key' => 'yahoo', 'url' => 'http://www.yahoo.com/'),
    array('key' => 'microsoft', 'url' => 'http://www.microsoft.com', 'followlocation' => true, 'content_types' => ''),
));
$base_grabber->grabSerial('analyze_page');

//$data = $base_grabber->getSerialContent(array('url' => 'http://www.google324.com/'));
//echo htmlentities(print_r($data, true));
//echo 'Error:', $base_grabber->getError();

echo '</pre>';
*/