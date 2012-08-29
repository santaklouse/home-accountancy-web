<?php

if (empty($base_docs))
{
    $base_docs = dirname(dirname(__FILE__)).'/';
}

require_once $base_docs . 'libs/url.class.php';

class cl_reg_expressions
{
    protected $encodings = null;

    /**
     * Function for grabbing attributes and values inside tags in markup languages (HTML, XML)
     */
    public function parse_tags($content, $tags = '', $get_attrs = array(), $where_attr = array(), $tag_value = false)
    {
        $attrs_expr = '';
        if (empty($get_attrs))
        {
            $get_attrs = array();
        }
        if (empty($where_attr))
        {
            $where_attr = array();
        }
        $any_attrs = array_diff_key(array_flip($get_attrs), $where_attr);
        foreach ($where_attr as $attr_name => $attr_value)
        {
            $attr_p = str_replace('-', '__', $attr_name);
            if ($attr_value === null)
            {
                $attrs_expr .= '(?:(?=[^>]*\s+(?P<'.$attr_p.'_attr>'.$attr_name.')(\s*=\s*(?P<'.$attr_p.'_value>".*?"|\'.*?\'|[^\'">\s]+))?))';
            }
            else
            {
                if (is_array($attr_value))
                {
                    $attr_value = '(?:'.implode('|', $attr_value).')';
                }
                $attrs_expr .= '(?:(?=[^>]*\s+(?P<'.$attr_p.'_attr>'.$attr_name.')\s*=\s*(?P<'.$attr_p.'_value>'.$attr_value.'|"'.$attr_value.'"|\''.$attr_value.'\')))';
            }
        }
        foreach ($any_attrs as $attr_name => $attr_value)
        {
            $attr_p = str_replace('-', '__', $attr_name);
            $attrs_expr .= '(?:(?=[^>]*\s+(?P<'.$attr_p.'_attr>'.$attr_name.')(\s*=\s*(?P<'.$attr_p.'_value>".*?"|\'.*?\'|[^\'">\s]+))?)|)';
        }
        $content_expr = $tag_value ? '(?P<content>.*?)<\/('.$tags.')>' : '';
        $reg_expr = '#<(?P<tag>'.$tags.')'.$attrs_expr.'[^>]*>'.$content_expr.'#si';
        $result = array();
        if (preg_match_all($reg_expr, $content, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE))
        {
            foreach($matches as $key => $match)
            {
                $result[$key] = array(
                    'tag' => $match['tag'][0],
                    'attr' => array(),
                    'pos' => array($match['tag'][0] => $match['tag'][1] - 1),
                );
                if (isset($match['content'][0]))
                {
                    $result[$key]['value'] = $match['content'][0];
                }
                $attrs = &$result[$key]['attr'];
                $poss = &$result[$key]['pos'];
                foreach ($get_attrs as $attr_name)
                {
                    $attr_p = str_replace('-', '__', $attr_name);
                    if (isset($match[$attr_p.'_attr']) && $match[$attr_p.'_attr'][0])
                    {
                        if (isset($match[$attr_p.'_value']))
                        {
                            $attrs[$attr_name] = trim($match[$attr_p.'_value'][0], '"\'');
                            $poss[$attr_name] = $match[$attr_p.'_value'][1];
                        }
                        else
                        {
                            $attrs[$attr_name] = null;
                        }
                    }
                }
            }
        }
        return $result;
    }

    public function parse_tag_urls(&$content, $search_tags, $search_attrs, $base_url = '', $where_attr = array())
    {
        $base_parts = parse_real_url($base_url);

        $result = array(
            'internal' => array(),
            'external' => array(),
        );
        $tags = $this->parse_tags($content, $search_tags, $search_attrs, $where_attr);
        foreach ($tags as $tag)
        {
            foreach ($search_attrs as $search_attr)
            {
                if (isset($tag['attr'][$search_attr]))
                {
                    $link_parts = parse_real_url($tag['attr'][$search_attr], false);
                    if ($link_parts !== false)
                    {
                        $merge_parts = merge_urls($base_parts, $link_parts);
                        $url = build_full_url($merge_parts);
                        if (!isset($result['internal'][$url]) && !isset($result['external'][$url]))
                        {
                            if (($base_parts['scheme'] == $merge_parts['scheme']) && ($base_parts['host'] == $merge_parts['host']))
                            {
                                $result['internal'][$url] = true;
                            }
                            else
                            {
                                $result['external'][$url] = true;
                            }
                        }
                    }
                }
            }
        }
        $result['internal'] = array_keys($result['internal']);
        $result['external'] = array_keys($result['external']);
        return $result;
    }

    private function replace_comments2blocks(&$matches)
    {
        return str_replace(array('<!--', '-->'), array('{<<LEFT>>}', '{<<RIGHT>>}'), $matches[0]);
    }

    public function remove_html_comments(&$content)
    {
        $content = preg_replace_callback('/<!\[CDATA\[.*?\]\]>/is', array($this, 'replace_comments2blocks'), $content);
        $content = preg_replace_callback('/<(script|style).*?>.*?<\/\\1>/is', array($this, 'replace_comments2blocks'), $content);
        $content = preg_replace('/<!--(?!\[)(.*?)-->/is', '', $content);
        $content = str_replace(array('{<<LEFT>>}', '{<<RIGHT>>}'), array('<!--', '-->'), $content);
    }
    
    private function remove_html_dates(&$content)
    {
        $content = preg_replace(
            '/(?<![\w+-:\/.])('.
                '\d{8}|'.
                '(?:[12]\d)?\d{2}[-\/.\s]\s?(?:\d{1,2}|[[:alpha:]]{3,}|[vix]+)[-\/.\s]\s?\d{1,2}|'.
                '(?:[12]\d)?\d{2}\.\s[[:alpha:]]{3,}\s\d{1,2}|'.
                '(?:\d{1,2}|[[:alpha:]]{3,})[-\/.\s]\s?(?:\d{1,2}|[vix]+)[-\/.\s]\s?(?:[12]\d)?\d{2}|'.
                '\d{1,2}(?:[[:alpha:]]{2})?\.?[\s*][[:alpha:]]*,?[\s-](?:[12]\d)?\d{2}|'.
                '([[:alpha:]]{3,},\s)?[[:alpha:]]{3,}\.?\s\d{1,2}(?:[[:alpha:]]{2})?,?\s(?:[12]\d)?\d{2}'.
            ')('.
                '(,\s|\/|T)(\d{1,2}:\d{2}(:\d{2})?|\d{2}h\s\d{2}m(\s\d{2}s)?)(\s*am|pm|Z)?'.
            ')?(?![\w+-])/is', '<date></date>', $content);
        
        $content = preg_replace(
            '/(?<![\w+-:])('.
                '((?:GMT|UTC|MSK)[+-])?(\d{1,2}:\d{2}(:\d{2})?|\d{1,2}h\s\d{1,2}m(\s\d{1,2}s)?)(\s*am|pm|Z)?'.
            ')(?![\w+-])/is', '<time></time>', $content);
    }
    
    public function replace_dynamic_content(&$content)
    {
        $split_words = preg_split('/(<\S.[^>]*>)/i', $content, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    
        $previos_word = false; 
        foreach($split_words as $key => $word)
        {
            $clean_word = trim($word);
            if ($clean_word)
            {
                if ($previos_word)
                {
                    if (preg_match('/<\/(script|style)>/is', $clean_word, $matches))
                    {
                        if ($matches[1] == $previos_word)
                        {
                            $previos_word = false;
                        }
                    }
                }
                else if ($clean_word{0} === '<')
                {
                    if (preg_match('/<(script|style).*>/is', $clean_word, $matches))
                    {
                        $previos_word = $matches[1];
                    }
                }
                else
                {
                    $this->remove_html_dates($word);
                    $split_words[$key] = $word;
                }
            }
        }
        $content = implode('', $split_words);
    }

    public function encode_from_content_type(&$content, $content_type, $to_encoding = 'UTF-8')
    {
        if (preg_match('#charset=([^ ">]+)#i', $content_type, $matches))
        {
            $from_encoding = strtoupper(trim($matches[1]));
            if ($from_encoding != $to_encoding && isset($this->encodings[$from_encoding]))
            {
                $content = mb_convert_encoding($content, $to_encoding, $from_encoding);
            }
            return $from_encoding;
        }
        return false;
    }

    public function convert_encoding(&$content, $to_encoding = 'UTF-8')
    {
        if ($this->encodings === null)
        {
            $this->encodings = array_flip(array_map('strtoupper', mb_list_encodings()));
        }
        $grab_results = $this->parse_tags($content, 'meta', array('http-equiv', 'content'), array('http-equiv' => 'content-type'));
        if (!empty($grab_results) && !empty($grab_item[0]['attr']['content']))
        {
            return $this->encode_from_content_type($content, $grab_item[0]['attr']['content'], $to_encoding);
        }
        return false;
    }
}

$reg_expressions = new cl_reg_expressions(); 

/*
//Example for parse_tags:

$text = '
<link rel="alternate" type="application/rss+xml" title="Все посты" href="/articles/rss/default.asp" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/style.css?3" />
<link rel="alternate" type="application/rss+xml" title="Xakep - Файлы" href="/articles/rss/default.asp?rss_cat=soft" />
<a href="LINK1" title=\'TITLE1\' hello=123 target=_BLANK1>TEXT1</a> ...some text ...
<a target="_BLANK2" href="LINK2" title="TITLE2">TEXT2</a> some more text...
<a title="TITLE3" href="LINK3">TEXT3</a>';

echo '<pre>';
print_r($reg_expressions->parse_tags($text, 'link', array('title'), array('rel' => array('alternate', 'stylesheet'))));
print_r($reg_expressions->parse_tags($text, 'a', array('href', 'hello'), array('hello' => null), true));
echo '</pre>';
*/
/*
echo '<pre>';
$content = file_get_contents('http://www.opennet.ru/opennews/art.shtml?num=29335');
$result = $reg_expressions->parse_tag_urls($content, 'a', array('href'), 'http://www.opennet.ru/opennews/');
echo htmlentities(print_r($result, true));
echo '</pre>';
*/