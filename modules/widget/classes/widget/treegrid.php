<?php defined('SYSPATH') or die('No direct script access.');

class Widget_Treegrid extends Widget_Grid {

    public function __construct($file = NULL, array $data = NULL)
    {
        parent::__construct($file, $data);

        $this->widget_id = 'treegrid_widget';
        $this->action_url = 'ajax/widget/treegrid/action';
        $this->data_url = 'ajax/widget/treegrid/data';
        $this->load_data = TRUE;
        $this->ajax_data = NULL;
        $this->load_once = TRUE;
        $this->level_icons = array();
        $this->level_actions = array();
        $this->max_actions_count = NULL;
    }

    protected function get_row_data(&$data, $id, $level = 0)
    {
        $rows = array();
        $number = 0;
        foreach ($data as $key => $row)
        {
            ++$number;
            $row_id = $id.'_'.$number;

            if (!empty($this->formated_data[$key]))
            {
                $row = $this->formated_data[$key] + $row;
            }
            $sub_rows = array();
            foreach ($row as $field => $field_value)
            {
                if (is_array($field_value) && !empty($field_value))
                {
                    $sub_rows = $this->get_row_data($field_value, $row_id, $level + 1);
                }
            }
            $row_data = NULL;
            foreach ($this->columns as $key => $column)
            {
                $value = isset($row[$column]) ? $row[$column] : '';
                if ($key == 0 && isset($this->level_icons[$level]))
                {
                    $value = array(
                        'image' => $this->level_icons[$level],
                        'value' => $value,
                    );
                }
                $row_data[] = $value;
            }
            if (!empty($this->row_actions))
            {
                $level_actions = $this->row_actions;
                if (isset($this->level_actions[$level]))
                {
                    $action_array = $this->level_actions[$level];
                    $level_actions = array_intersect_key($level_actions, array_flip($action_array));
                }
                $options = $this->get_ordered_row_actions(array(
                    'row_actions' => $level_actions,
                    'action_url' => $this->action_url,
                    'key_fields' => $this->key_fields,
                    'extra_fields' => $this->extra_fields,
                    'client_name' => $this->client_name,
                ), $row);
                $row_data[] = json_encode($options);
            }
            $new_row = array(
                'id' => $row_id,
                'data' => $row_data,
                'userdata' => isset($row['userdata']) ? $row['userdata'] : array(),
            );
            if ($sub_rows !== NULL && !empty($sub_rows))
            {
                $new_row['rows'] = $sub_rows;
                $new_row['xmlkids'] = count($sub_rows);
            }
            $rows[] = $new_row;
        }
        return $rows;
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

        $id = $pos_start;
        $rows = $this->get_row_data($this->data, $this->widget_id);
        $result = array(
            'total_count' => $this->total_items,
            'pos' => $pos_start,
            'rows' => $rows,
            'table_options' => isset($this->table_options) ? $this->table_options : array(),
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
        if (isset($this->styles))
        {
            $this->styles->add(array(
                'css/dhtmlx/grid',
                'css/dhtmlx/toolbar',
                'vendor/dhtmlx/dhtmlxGrid/codebase/ext/dhtmlx_extdrag',
            ));
        }
        if (isset($this->scripts))
        {
            $this->scripts->add(array(
                'vendor/dhtmlx/dhtmlxGrid/codebase/dhtmlxcommon',
                'vendor/dhtmlx/dhtmlxGrid/codebase/dhtmlxgrid',
                'vendor/dhtmlx/dhtmlxGrid/codebase/dhtmlxgridcell',
                'vendor/dhtmlx/dhtmlxGrid/codebase/ext/dhtmlxgrid_srnd',
                'vendor/dhtmlx/dhtmlxGrid/codebase/ext/dhtmlxgrid_splt',
                'vendor/dhtmlx/dhtmlxGrid/codebase/ext/dhtmlxgrid_mcol',
                'vendor/dhtmlx/dhtmlxGrid/codebase/ext/dhtmlxgrid_drag',
                'vendor/dhtmlx/dhtmlxGrid/codebase/ext/dhtmlx_extdrag',
                'js/dhtmlx/common',
                'js/dhtmlx/grid/pagination',
                'js/dhtmlx/grid/filter',
                'js/dhtmlx/grid/json',
                'js/dhtmlx/grid/hmenu',
                'js/dhtmlx/grid/excell_options',
                'js/dhtmlx/grid/group',
                'vendor/dhtmlx/dhtmlxToolbar/codebase/dhtmlxtoolbar', // toolbar
                'vendor/dhtmlx/dhtmlxTreeGrid/codebase/dhtmlxtreegrid', // treegrid
                'vendor/dhtmlx/dhtmlxTreeGrid/codebase/ext/dhtmlxtreegrid_lines',
                'js/dhtmlx/grid/extensions',
                'js/dhtmlx/grid/treegrid',
            ));
        }
        if ($this->max_actions_count === NULL && !empty($this->level_actions))
        {
            $this->max_actions_count = 0;
            foreach ($this->level_actions as &$actions)
            {
                if (count($actions) > $this->max_actions_count)
                {
                    $this->max_actions_count = count($actions);
                }
            }
        }
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
