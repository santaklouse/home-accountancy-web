<?php

if (empty($base_docs))
{
    $base_docs = dirname(dirname(__FILE__)).'/';
}
require_once $base_docs . '/libs/spider.class.php';

class cl_keywords_grabber extends cl_spider
{
    protected $grab_keywords = array();

    function __construct()
    {
        $this->params += array(
            'max_page_phrases' => 1000,
            'ignore_phrases' => array(),
        );
    }

    /**
    * Get a list of a keywords
    */
    public function grabKeywords($url)
    {
        global $language;

        $time_start = microtime(true);

        $this->grab_keywords = array();
        $links_count = 0;
        $error = false;

        $grab_result = $this->grab_urls($url, array($this, 'parseResponseData'));
        $processing_results = $this->get_processing_results();
        if ($grab_result)
        {
            $links_count = $this->get_found_urls_count();
            foreach ($processing_results as $url => &$site_info)
            {
                foreach ($site_info as $grab_type => &$phrases)
                {
                    foreach ($phrases as $phrase => $count)
                    {
                        if (isset($this->grab_keywords[$grab_type][$phrase]))
                        {
                            $this->grab_keywords[$grab_type][$phrase] += $count;
                        }
                        else
                        {
                            $this->grab_keywords[$grab_type][$phrase] = $count;
                        }
                    }
                }
            }
        }
        else if (empty($processing_results))
        {
            $error = 'This site keywords were not found.';
        }
        else
        {
            $error = 'The site does not exist.';
        }

        $total_words = 0;
        $found_words = 0;
        if (!$error)
        {
            foreach($this->grab_keywords as $grab_type => &$phrases)
            {
                if (isset($this->params['options'][$grab_type]) && !empty($phrases))
                {
                    $total_words += count($phrases);
                    $this->sortPhrases($phrases);
                    $phrases = array_slice($phrases, 0, $this->params['options'][$grab_type]['count'], true);
                    $found_words += count($phrases);
                }
            }
        }

        return array(
            'error' => $error,
            'found_words' => $found_words,
            'total_words' => $total_words,
            'links_count' => $links_count,
            'execute_time' => round(microtime(true) - $time_start, 2)
        );
    }
    
    public function getGrabKeywords($with_types = false)
    {
        if ($with_types)
        {
            return $this->grab_keywords;
        }
        $result = array();
        foreach ($this->grab_keywords as $grab_type => &$phrases)
        {
            foreach ($phrases as $phrase => $count)
            {
                if (isset($result[$phrase]))
                {
                    $result[$phrase] += $count;
                }
                else
                {
                    $result[$phrase] = $count;
                }
            }
        }
        return $result;
    }

    public function parseResponseData(&$response, $options)
    {
        global $reg_expressions;
        if($reg_expressions === NULL)
            $reg_expressions = new cl_reg_expressions();

        $result = array();
        $meta_types = array();
        if (isset($options['meta']))
        {
            $meta_types[] = 'keywords';
        }
        if (isset($options['description']))
        {
            $meta_types[] = 'description';
        }
        if (!empty($meta_types))
        {
            $grab_results = $reg_expressions->parse_tags($response['content'], 'meta', array('name', 'content'), array('name' => $meta_types));
            foreach ($grab_results as $grab_item)
            {
                if (!empty($grab_item['attr']['content']))
                {
                    $attr_content = &$grab_item['attr']['content'];
                    if ($grab_item['attr']['name'] == 'keywords')
                    {
                        $this->cleanText($attr_content);
                        $result['meta'] = $this->findMetaPhrases($attr_content);
                    }
                    else if ($grab_item['attr']['name'] == 'description')
                    {
                        $this->cleanText($attr_content);
                        $result['description'] = $this->findPhrases($attr_content, $options['description']['types']);
                    }
                }
            }
        }
        if (isset($options['body']))
        {
            $grab_results = $reg_expressions->parse_tags($response['content'], 'body', null, null, true);
            if (empty($grab_results))
            {
                $body_content = $response['content'];
            }
            else
            {
                $body_content = &$grab_results['value'];
            }
            $this->cleanText($body_content);
            $result['body'] = $this->findPhrases($body_content, $options['body']['types']);
        }
        return $result;
    }

    /**
     * Remove HTML tags
     */
    protected function cleanText(&$text)
    {
        remove_content_comments($text);
        remove_content_tags($text, array('script', 'style'));
        $text = strip_tags($text);
        $text = html_entity_decode($text, ENT_NOQUOTES, 'UTF-8');
    }

    /**
     * Split text to words array
     */
    protected function splitToWords(&$text)
    {
        $words = array();
        $text = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($text as $word)
        {
            $word = trim($word, '#$%&-@«»');
            if ($word)
            {
                $word = mb_strtolower($word, 'UTF-8');
                if (!isset($this->params['ignore_phrases'][$word]))
                {
                    $words[] = $word;
                }
            }
        }
        return $words;
    }

    /**
     * Split text to word groups
     */
    protected function splitToWordGroups(&$text, $splitters = '.,"\'?!;:()*+\/<>=@\[\]\\^{}|~')
    {
        $word_groups = array();
        $text = preg_split('/['.$splitters.']+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($text as $block)
        {
            $word_groups[] = $this->splitToWords($block);
        }
        return $word_groups;
    }

    /**
     * Calculate word occurrences
     */
    protected function addPhrase($phrase, &$phrases)
    {
        if (mb_strlen($phrase, 'UTF-8') > 2 && !is_numeric($phrase))
        {
            if (isset($phrases[$phrase]))
            {
                $phrases[$phrase]++;
            }
            else
            {
                $phrases[$phrase] = 1;
            }
        }
    }
    
    protected function findMetaPhrases(&$text)
    {
        $word_groups = $this->splitToWordGroups($text, ',');

        $phrases = array();
        foreach ($word_groups as $group)
        {
            $phrase = implode(' ', $group);
            $this->addPhrase($phrase, $phrases);
            if (count($phrases) >= $this->params['max_page_phrases'])
            {
                return $phrases;
            }
        }
        return $phrases;
    }

    /**
     * Find phrases with word combinations
     */
    protected function findPhrases(&$text, $word_counts)
    {
        $word_groups = $this->splitToWordGroups($text);
        $max_words = 3;

        $phrases = array();
        foreach ($word_groups as $group)
        {
            $total = count($group);
            for ($x = 0; $x < $total; $x++)
            {
                $phrase = '';
                $i_max = $x + $max_words;
                if ($i_max > $total)
                {
                    $i_max = $total;
                }
                for ($i = $x; $i < $i_max; $i++)
                {
                    if ($phrase)
                    {
                        $phrase .= ' ';
                    }
                    $phrase .= $group[$i];
                    $word_count = $max_words - ($i_max - $i) + 1;
                    if (isset($word_counts[$word_count]))
                    {
                        $this->addPhrase($phrase, $phrases);
                        if (count($phrases) >= $this->params['max_page_phrases'])
                        {
                            return $phrases;
                        }
                    }
                }
            }
        }
        return $phrases;
    }

    protected function compareItems(&$i1, &$i2)
    {
        if ($i1[0] == $i2[0])
        {
            return ($i1[1] > $i2[1] ? -1 : ($i1[1] > $i2[1]) ? 1 : 0);
        }
        return ($i1[0] > $i2[0] ? -1 : 1);
    }

    protected function sortPhrases(&$phrases)
    {
        $index = 0;
        foreach($phrases as &$item)
        {
            $item = array($item, $index++);
        }

        uasort($phrases, array($this, 'compareItems'));

        foreach($phrases as &$item)
        {
            $item = $item[0];
        }
    }
}

$keywords_grabber = new cl_keywords_grabber();
/*
set_time_limit(300);

echo '<pre>';
$keywords_grabber->set_params(array(
    'grab_params' => array(
        'utf8' => true,
        'group_count' => 20,
        'group_delay' => 0,
    ),
    'max_level' => 2,
    'max_page_links' => array(
        1 => 100,
    ),
    'max_level_links' => array(
        1 => 200,
    ),
    'options' => array(
        'meta' => array(
            'count' => 1000,
        ),
        'description' => array(
            'count' => 100,
            'types' => array(1 => true, 2 => true, 3 => true),
        ),
        'body' => array(
            'count' => 100,
            'types' => array(1 => true, 2 => true, 3 => true),
        ),
    ),
    'internal_links' => true,
    'external_links' => true,
));
$results = $keywords_grabber->grabKeywords('http://podrobnosti.ua/');
print_r($results);
if (!$results['error'])
{
    //print_r($keywords_grabber->getGrabKeywords());
    print_r($keywords_grabber->getGrabKeywords(true));
    print_r($keywords_grabber->get_found_urls(false, true));
}
else
{
    echo $results['error'];
}
echo '</pre>';
*/