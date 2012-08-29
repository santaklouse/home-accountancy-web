<?php defined('SYSPATH') or die('No direct script access.');

class Widget_Grid extends Widget_Table {

    public function __construct($file = NULL, array $data = NULL)
    {
        parent::__construct($file, $data);

        $this->widget_id = 'grid_widget';
        $this->action_url = 'ajax/widget/grid/action';
        $this->data_url = 'ajax/widget/grid/data';
        $this->load_data = TRUE;
        $this->ajax_data = NULL;
        $this->load_once = TRUE;
    }

    public function set_page_info($items_per_page, $current_page = NULL)
    {
        $pos_start = ($current_page === NULL)
            ? Input::get('posStart', 0)
            : ($current_page - 1) * $items_per_page;

        $this->current_page = floor($pos_start / $items_per_page) + 1;
        $this->items_per_page = $items_per_page;
    }

    public function get_page_info()
    {
        $config = Kohana::$config->load('pagination');
        $this->set_page_info($config->table['items_per_page']);
    }

    protected function get_ordered_row_actions($params = array(), &$row)
    {
        $options = array(
            'items' => array(),
        );
        $global_url = $params['action_url'];
        if ($global_url)
        {
            $options['url'] = $global_url;
        }
        foreach ($params['row_actions'] as $action_name => $action_item)
        {
            if (isset($action_item['custom']))
            {
                $custom_item = call_user_func($action_item['custom'], $row);
                if (!empty($custom_item))
                {
                    $action_item = $custom_item + $action_item;
                } 
            }
            if (empty($action_item['icon']))
            {
                continue;
            }
            $action_url = Arr::get($action_item, 'action_url');
            if (empty($global_url) && empty($action_url))
            {
                continue;
            }
            $row_keys = array();
            foreach ($params['key_fields'] as $row_key)
            {
                $row_keys[] = Arr::get($row, $row_key, '');
            }
            $row_extra = array();
            foreach ($params['extra_fields'] as $extra_field)
            {
                $row_extra[$extra_field] = Arr::get($row, $extra_field, '');
            }
            $item = array(
                'icon' => $action_item['icon'],
                'data' => array(
                    'action' => $action_name,
                    'key' => $params['key_fields'],
                    'val' => $row_keys,
                ),
            );
            if (!empty($row_extra))
            {
                $item['data']['extra'] = $row_extra;
            }
            if ($action_url)
            {
                $item['url'] = $action_url;
            }
            if (!empty($params['client_name']))
            {
                $item['data']['client'] = $params['client_name'];
            }
            if (isset($action_item['title']))
            {
                $item['title'] = $action_item['title'];
            }
            if (isset($action_item['function']))
            {
                $item['function'] = $action_item['function'];
            }
            $options['items'][] = $item;
        }
        return $options;
    }

    public function get_ajax_page()
    {
        $pos_start = $this->current_page;
        if ($pos_start > 0)
        {
            --$pos_start;
        }
        $pos_start *= $this->items_per_page;

        $this->before_render();

        $rows = array();
        $id = $pos_start;
        foreach ($this->data as $key => $row)
        {
            if (!empty($this->formated_data[$key]))
            {
                $row = $this->formated_data[$key] + $row;
            }
            $data = array();
            foreach ($this->columns as $column)
            {
                $data[] = isset($row[$column]) ? $row[$column] : '';
            }
            if (!empty($this->row_actions))
            {
                $options = $this->get_ordered_row_actions(array(
                    'row_actions' => $this->row_actions,
                    'action_url' => $this->action_url,
                    'key_fields' => $this->key_fields,
                    'extra_fields' => $this->extra_fields,
                    'client_name' => $this->client_name,
                ), $row);
                $data[] = json_encode($options);
            }
            $rows[] = array(
                'id' => $id++,
                'data' => $data,
                'userdata' => isset($row['userdata'])?$row['userdata']:array()
            );
        }
        $result = array(
            'total_count' => $this->total_items,
            'pos' => $pos_start,
            'rows' => $rows,
            'table_options' => isset($this->table_options)?$this->table_options:array()
        );
        if (!empty($this->groups))
        {
            $group = reset($this->groups);
            reset($group['key']);
            $key_field = key($group['key']);

            $items = array();
            foreach ($this->groups as &$group)
            {
                if (isset($group['key'], $group['title']))
                {
                    $options = array();
                    if (!empty($group['row_actions']))
                    {
                        $options = $this->get_ordered_row_actions(array(
                            'row_actions' => $group['row_actions'],
                            'action_url' => $this->action_url,
                            'key_fields' => array_keys($group['key']),
                            'extra_fields' => $this->extra_fields,
                            'client_name' => $this->client_name,
                        ), $group['key']);
                    }
                    $items[] = array(
                        'key' => $group['key'][$key_field],
                        'title' => $group['title'],
                        'options' => $options,
                    );
                }
            }
            $result['groups'] = array(
                'key' => $key_field,
                'items' => $items,
            );
        }
        return $result;
    }

    public function render($file = NULL)
    {
        if (!empty($this->data))
        {
            $this->load_data = FALSE;
            $this->ajax_data = $this->get_ajax_page();
        }
        else
        {
            $this->before_render();
        }
        return Widget_Table::render($file);
    }
}
