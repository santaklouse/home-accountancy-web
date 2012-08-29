<?php defined('SYSPATH') or die('No direct script access.');

class Widget_Table extends Widget {

    protected $_data = array(
        'widget_id' => 'table_widget',
        'action_url' => 'ajax/widget/table',
        'data' => array(),
        'formated_data' => array(),
        'fields' => array(),
        'columns' => array(),
        'key_fields' => array(),
        'extra_fields' => array(),
        'extra_data' => array(),
        'table_title' => '',
        'item_name' => '',
        'groups' => array(),
        'header_groups' => array(),
        'filter' => FALSE,
        'row_actions' => array(),
        'errors' => array(),
        'order_by' => array(),
        'load_once' => FALSE,
        'configure' => array(),
        'time_out_update' => NULL,
        'additional_top_actions' => array(),
        // client data
        'client_name' => FALSE,
        'min_rows' => 5,
        // pagination
        'total_items' => 0,
        'items_per_page' => NULL,
        'current_page' => 1,
    );

    public function __construct($file = NULL, array $data = NULL)
    {
        parent::__construct($file, $data);

        $this->get_page_info();
    }

    public function get_page_info()
    {
        $pagination = Pagination::factory(array(), 'table');
        $this->current_page = $pagination->current_page;
        $this->items_per_page = $pagination->items_per_page;
    }

    public function get_client_params()
    {
        $result = $this->load_once
            ? array()
            : array(
                'paginate' => array(
                    'page' => $this->current_page - 1,
                    'per_page' => $this->items_per_page,
                ),
            );
        $sort_field = Input::get('sort_field');
        if ($sort_field && isset($this->fields[$sort_field]))
        {
            $order = $sort_field;
            $sort_dir = Input::get('sort_dir');
            if ($sort_dir != 'asc')
            {
                $order .= ' desc';
            }
            $result['order_by'] = array($order);
        }
        $filter_field = Input::get('filter_field');
        if ($filter_field && isset($this->fields[$filter_field]))
        {
            $filter_val = Input::get('filter_val');
            $result['filter'] = array(
                $filter_field => $filter_val
            );
        }
        return $result;
    }

    public function set_header($identifier = FALSE)
    {
        $header_data = FALSE;//Client::factory('SiteHeader')->get_header_data($identifier);
        if (!$header_data)
        {
            return FALSE;
        }
        $title = Arr::get($header_data, 'title_text', $this->item_name);
        $icon = ($header_data['title_image'])
            ? '<img src="'.$header_data['title_image'].'" align="absmiddle" height=18 width=18/>'
            : '';
        $this->table_title = $icon. ' ' .$title;

        $info_icon = Tpl::image('img/box-nav-help.gif',array(
            'width' => '18',
            'height' => '18',
            'id' => Arr::get($header_data, 'site_blog_identificator', ''),
            'class' => 'title-info-icon show-table-info',
            'title' => 'More info about this table',
        ));

        $this->configure['info_icon'] = $info_icon;
    }

    public function add_data($data = array())
    {
        if (isset($data['client_name']))
        {
            $this->client_name = $data['client_name'];
        }
        if (isset($data['item_name']))
        {
            if (empty($this->table_title))
            {
                $this->table_title = $data['item_name'];
            }
            if (empty($this->item_name))
            {
                $this->item_name = $data['item_name'];
            }
        }
        if (isset($data['fields']))
        {
            $this->fields = Arr::merge($this->fields, $data['fields']);
            if (empty($this->columns))
            {
                $this->columns = array_keys($data['fields']);
            }
            if (empty($this->key_fields))
            {
                $this->key_fields = array_keys(Arr::parse($data['fields'], NULL, 'key'));
            }
            if (empty($this->extra_fields))
            {
                $this->extra_fields = array_keys(Arr::parse($data['fields'], NULL, 'extra_field'));
            }
        }
        if (isset($data['data']) && is_array($data['data']) && isset($data['fields']))
        {
            $this->data = $data['data'];

            foreach ($data['fields'] as $field => $dataset)
            {
                if (isset($dataset['type']) && ($dataset['type'] == 'image')
                    && isset($dataset['path']) && !empty($data['data']))
                {
                    foreach ($this->data as $id => &$items)
                    {
                        $path = 'files/' . $dataset['path'] . strtolower($this->client_name);
                        if (array_key_exists('image', $items))
                        {
                            continue;
                        }
                        foreach ($this->key_fields as $key)
                        {
                            $id = $items[$key];
                            $path .= '_' . $id;
                        }
                        $path .= '_preview.png';
                        if (file_exists($path))
                        {
                            $items[$field] = $path;
                        }
                    }
                }
            }

            $this->formated_data = Client::format_data($this->data, $data['fields'], $this->columns);
        }
        if (isset($data['total_items']))
        {
            $this->total_items = $data['total_items'];
        }
        if (isset($data['errors']))
        {
            $this->errors = array_merge($this->errors, $data['errors']);
        }
        return $this;
    }

    protected function before_render()
    {
        if (!empty($this->header_groups))
        {
            $columns = array();
            foreach ($this->header_groups as $header_group)
            {
                if (!empty($header_group['items']))
                {
                    foreach ($header_group['items'] as $field => $item)
                    {
                        $columns[] = $field;
                    }
                }
            }
            $this->columns = $columns;
        }
        if (!empty($this->groups))
        {
            $group_keys = array();
            foreach ($this->groups as $id => $group)
            {
                if (!empty($group['key']))
                {
                    foreach ($group['key'] as $field => $value)
                    {
                        $group_keys[$field] = TRUE;
                    }
                }
            }
            foreach ($group_keys as $field => $value)
            {
                if (!in_array($field, $this->columns) && isset($this->fields[$field]))
                {
                    $this->columns[] = $field;
                    $this->fields[$field]['hidden'] = TRUE;
                }
            }
        }
    }

    public function render($file = NULL)
    {
        $this->before_render();
        return parent::render($file);
    }
}
