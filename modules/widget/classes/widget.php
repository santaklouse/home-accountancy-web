<?php defined('SYSPATH') or die('No direct script access.');

class Widget extends View {

    public static function factory($file = NULL, array $data = NULL)
    {
        $class = get_called_class();
        return new $class($file, $data);
    }

    public function __construct($file = NULL, array $data = NULL)
    {
        if ($file === NULL)
        {
            $file = strtolower(substr(get_called_class(), strlen(get_class()) + 1));
        }
        $this->set_filename('widget/'.$file);

        if ($data !== NULL)
        {
            $this->_data = $data + $this->_data;
        }
    }
}
