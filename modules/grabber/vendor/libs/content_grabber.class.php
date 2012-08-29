<?php

if (empty($base_docs))
{
    $base_docs = dirname(dirname(__FILE__)).'/';
}
if(file_exists($base_docs . 'libs/memcache.class.php'))
{
  require_once $base_docs . 'libs/memcache.class.php';  
}
require_once $base_docs . 'libs/base_grabber.class.php';

class cl_content_grabber extends cl_base_grabber
{
    protected $memcache = false;
    protected $memcacheKey = '';

    // Memcache functions

    public function enableMemcache($enable = true, $memcacheKey = '', $memcacheData = array())
    {
        global $memcache;

        if ($enable)
        {
            $this->memcacheKey = $memcacheKey;
            $this->memcache = $memcacheData;
        }
        else
        {
            $this->memcache = false;
        }
    }

    protected function updateMemcache(&$data)
    {
        global $memcache;

        if ($this->memcache)
        {
            $this->memcache['number'] = $data['number'];
            $this->memcache['total'] = $data['total'];
            $this->memcache['runtime'] = $data['runtime'];
            $memcache->set($this->memcacheKey, $this->memcache);
        }
    }

    protected function releaseMemcache()
    {
        global $memcache;

        if ($this->memcache)
        {
            $memcache->delete($this->memcacheKey);
            $this->memcache = false;
        }
    }
}

$content_grabber = new cl_content_grabber();
