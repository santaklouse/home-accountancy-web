<?php defined('SYSPATH') or die('No direct script access.');

class Widget_Piwik extends Widget {

    protected $_data = array(
        'domain_id' => 0,
        'period' => 'day',
        'date' => 'today',
        'table_columns'=> array(),
        'chart_type' => 'line',
        'columns' => '',
        'segment' => '',
        'widget_title' => '',
        'chart_title' => '',
        'chart_y_title' => '',
        'method' => ''
    );
    protected $available_columns = array();
    public function __construct($file = NULL, array $data = NULL)
    {
        $config_item = strtolower($file);
        $file = str_replace('.', '/', $config_item);
        parent::__construct('piwik/'.$file, $data);
        $config = Kohana::$config->load('piwik')->get('default');
        $this->available_columns = Arr::path($config,$config_item,array());
    }
    public function get_tables_fields($custom_tables = array())
    {
        if(empty($custom_tables))
            return $this->available_columns;
        foreach ($this->available_columns as $key => $value) 
        {
            if(in_array($key, $columns))
                $this->available_columns[$key]['hidden'] = FALSE;
            else
                $this->available_columns[$key]['hidden'] = TRUE;
        }

    }
    public function render()
    {
    /*    //will remove
        if(empty($this->table_columns))
        {
            foreach ($this->available_columns as $key => $value) 
            {
                if($value == TRUE)
                    $this->table_columns[] = $key;
            }
        }
        else
        {
            // check if column exists at all
            $columns  = array_keys($this->available_columns);
            foreach ($this->table_columns as $key => $column) 
            {
                if(!in_array($column, $columns))
                    unset($this->table_columns[$key]);
            }
        }*/
       return parent::render();
    }
}