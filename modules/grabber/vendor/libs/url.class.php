<?php

/**
 * Check and return URL scheme
 */
function find_url_scheme($url)
{
    if ($url && preg_match('/^([[:alpha:]]+):(.*)$/', $url, $matches))
    {
        return $matches[1];
    }
    return false;
}

/**
 * Function for safe parsing global/relative URLs
 */
function parse_real_url($url, $strict = true, $allow_schemes = array(), $defaults = array())
{
    if (!$url)
    {
        return false;
    }
    $url_scheme = find_url_scheme($url);
    if ($url_scheme)
    {
        if (empty($allow_schemes))
        {
            $allow_schemes = array('http', 'https');
        }
        if (in_array($url_scheme, $allow_schemes) && filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED))
        {
            return parse_url($url);
        }
        return false;
    }
    else if ($strict)
    {
        return false;
    }
    
    $scheme = isset($defaults['scheme']) ? $defaults['scheme'] : 'scheme';
    $host = isset($defaults['host']) ? $defaults['host'] : 'host';
    $added_slash = false;
    if ($host && $url[0] != '/')
    {
        $url = '/'.$url;
        $added_slash = true;
    }
    $url = $scheme.'://'.$host.$url;
    if (filter_var($url, FILTER_VALIDATE_URL))
    {
        $result = parse_url($url);
        if (!isset($defaults['scheme']))
        {
            unset($result['scheme']);
        }
        if (!isset($defaults['host']))
        {
            unset($result['host']);
        }
        if ($added_slash && isset($result['path']) && $result['path'][0] == '/')
        {
            $result['path'] = substr($result['path'], 1);
        }
        return $result;
    }
    return false;
}

/**
 * Build url from parse_url result
 */
function build_full_url($url_parts)
{
    $url = '';
    if (isset($url_parts['host']))
    {
        if (isset($url_parts['scheme']))
        {
            $url .= $url_parts['scheme'] . '://';
        }
        if (isset($url_parts['user']))
        {
            $url .= $url_parts['user'];
            if (isset($url_parts['pass']))
            {
                $url .= ':' . $url_parts['pass'];
            }
            $url .= '@';
        }
        $url .= $url_parts['host'];
        if (isset($url_parts['port']))
        {
            $url .= ':' . $url_parts['port'];
        }
    }
    if (isset($url_parts['path']))
    {
        if ($url_parts['path'] == '' || $url_parts['path'][0] != '/')
        {
            $url .= '/' . $url_parts['path'];
        }
        else
        {
            $url .= $url_parts['path'];
        }
        if (isset($url_parts['query']))
        {
            $url .= '?' . $url_parts['query'];
        }
        if (isset($url_parts['fragment']))
        {
            $url .= '#' . $url_parts['fragment'];
        }
    }
    return $url;
}

/**
 * Overwrite new_url parts over base_url
 * TODO: Add support for:
 * http://host.com/forum/./viewtopic.php?f=14&p=304877
 * http://host.com/forum/../viewtopic.php?f=14&p=304877
 */
function merge_urls($base_url, $new_url)
{
    $result_url = $new_url + array_intersect_key($base_url, array('scheme' => '', 'host' => ''));

    if (isset($new_url['path']) && isset($base_url['path']))
    {
        if ($new_url['path'] == '' || $new_url['path'][0] != '/')
        {
            $base_path = $base_url['path'];
            if (substr($base_path, -1) != '/')
            {
                $base_path = str_replace(basename($base_path), '', $base_path);
            }
            $result_url['path'] = rtrim($base_path, '/') . '/' . ltrim($new_url['path'], '/');
        }
    }
    return $result_url;
}

/**
 * Parse safe URL, also without scheme prefix
 * (be careful, it's not real URL by default, because real URL always includes scheme)
 */
function parse_url_ext($url, $strict = false)
{
    if ($strict)
    {
        return parse_real_url($url);
    }
    return parse_real_url($url, false, false, array('scheme' => 'http', 'host' => ''));
}

/**
 * Get safe URL, also without scheme prefix
 * (be careful, it's not real URL by default, because real URL always includes scheme)
 */
function get_safe_url($url, $strict = false)
{
    $url_parts = parse_url_ext($url, $strict);
    if ($url_parts)
    {
        return build_full_url($url_parts);
    }
    return '';
}

/**
 * Get safe domain from URL, also without scheme prefix
 * (be careful, it's not real URL by default, because real URL always includes scheme)
 */
function get_safe_domain($url, $strict = false)
{
    $url_parts = parse_url_ext($url, $strict);
    if ($url_parts)
    {
        return $url_parts['host'];
    }
    return '';
}

/**
 * Get IP by URL, also without scheme prefix
 * (be careful, it's not real URL by default, because real URL always includes scheme)
 */
function get_ip_by_url($url, $strict = false)
{
    $url_parts = parse_url_ext($url, $strict);
    if ($url_parts !== false && isset($url_parts['host']))
    {
        return gethostbyname($url_parts['host']);
    }
    return false;
}

function remove_content_tags(&$content, $tags = array())
{
    $tags_expr = implode('|', $tags);
    $content = preg_replace('/<('.$tags_expr.')[^>]*?>.*?<\/\1>/si', '', $content);
}

function remove_www($domain)
{
    $domain = preg_replace("/^www\./ix", "", $domain);
    return $domain;
}
function remove_content_comments(&$content)
{
    $content = preg_replace('/<!--(.*?)-->/is', '', $content);
}

function close_connection($data)
{
    ob_start();
    echo $data;
    $size = ob_get_length();
    header("Content-Length: $size");
    header('Connection: close');
    ob_end_flush();
    ob_flush();
    flush();
}