<?php
$header1 = array();
$header2 = array();
$filters = array();
if (!empty($header_groups))
{
    foreach ($header_groups as $header_group)
    {
        $first_item = TRUE; 
        foreach ($header_group['items'] as $field => $item)
        {
            if (!empty($fields[$field]['hidden']))
            {
                continue;
            }
            if ($first_item)
            {
                $header1[$field] = $header_group['title'];
                $first_item = FALSE;
            }
            else
            {
                $header1[$field] = '#cspan';
            }
        }
        if (!empty($header_group['title']) && count($header_group['items']) <= 1)
        {
            foreach ($header_group['items'] as $field => $item)
            {
                if (isset($fields[$field], $fields[$field]['label'])
                    && $header_group['title'] == $fields[$field]['label'])
                {
                    $fields[$field]['label'] = '';
                }
            }
        }
    }
}
$table_columns = array();
foreach ($columns as $field)
{
    $item = Arr::get($fields, $field, array());
    $column_data = array(
        'id' => $field,
        'align' => 'center',
        'label' => $field,
    );
    if (isset($item['label']))
    {
        if (isset($item['icon']))
        {
            $item['label'] = '<span class="'. $item['icon']. '"></span>'.$item['label'];
        }
        $column_data['label'] = $item['label'];
    }
    $length = NULL;
    if (isset($item['type']))
    {
        switch ($item['type'])
        {
            case 'int':
                $length = 8;
                break;
            case 'float':
                $length = 11;
                break;
            case 'text':
            case 'html':
            case 'textarea':
                $column_data['align'] = 'left';
                break;
            case 'date':
                $length = 8;
                break;
            case 'datetime':
                $length = 17;
                break;
            case 'image':
                $length = 15;
                break;
            case 'checkbox':
                $length = 5;
                break;
        }
    }
    if (!empty($item['length']))
    {
        $length = $item['length'];
    }
    $column_data['width'] = empty($length) ? '*' : $length * 7;
    $type = $item['type'];
    if (isset($item['enum']) || isset($item['translations']))
    {
        $type = 'text';
    }
    $grid_type = 'str';
    switch ($type)
    {
        case 'image':
            $grid_type = FALSE;
            break;
        case 'int':
        case 'float':
            $grid_type = 'int';
            break;
    }
    if ($grid_type)
    {
        if (!isset($item['sort']) || $item['sort'])
        {
            $column_data['sort'] = $grid_type;
        }
        if (!isset($item['filter']) || $item['filter'])
        {
            $column_data['filter'] = $grid_type;
        }
    }
    if (!empty($item['hidden']))
    {
        $column_data['hidden'] = 1;
    }
    if (!empty($header1))
    {
        $header2[] = $column_data['label'];
        $column_data['label'] = Arr::get($header1, $field, '');
    }
    $filter = '';
    if (isset($item['filter']) && is_string($item['filter']))
    {
        switch ($item['filter'])
        {
            case 'select':
                $filter = '#select_filter';
                break;
            default:
                $filter = '#text_filter';
                break;
        }
    }
    else if (isset($column_data['filter']))
    {
        $filter = isset($item['enum']) ? '#select_filter' : '#text_filter';
    }
    $filters[] = $filter;
    $table_columns[] = $column_data;
}
if (!empty($row_actions))
{
    $width = count($row_actions) * 24 + 24;
    if ($width < 72)
    {
        $width = 72;
    }
    $table_columns[] = array(
        'label' => __('Actions'),
        'align' => 'center',
        'width' => $width,
        'type' => 'options',
    );
    if (!empty($header1))
    {
        $header2[] = '';
    }
    if (!empty($filters))
    {
        $filters[] = '';
    }
}
?>
<div class="box">
    <div class="box-title">
        <?php
        echo '<span';
        if (isset($title_icon))
        {
            echo ' class="', $title_icon, '"';
        }
        echo '>';
        if (isset($table_title))
        {
            echo $table_title;
        }
        echo '</span>';
        ?>
        <div class="l"></div><div class="r"></div>
        <?php
        if(isset($configure))
        {
            echo '<div class="configure-tools">';
            echo Arr::get($configure, 'info_icon', '');
            if(isset($configure['reset_config']))
            {
                $config = $configure['reset_config'];
                echo '<a class="tools close_btn" href="#" ';
                    echo ' onclick="grid_request(\'',$config['url'], '\',';
                    echo '{';
                        echo '\'key\':[\'', implode("','", $key_fields), '\']';
                        if (!empty($client_name))
                        {
                            echo ",'client':'", $client_name, "'";
                        }
                        echo ",'action':'",$config['action'], "'";
                        echo ",'widget_id': '".$widget_id."'";
                    echo '}, \'', $widget_id, '\');return false;" href="#"></a>';
            }
            if(isset($configure['make_config']))
            {
                $config = $configure['make_config'];
                echo '<a class="tools configure_btn"';
                echo ' onclick="grid_request(\'',$config['url'], '\',';
                    echo '{';
                        echo '\'key\':[\'', implode("','", $key_fields), '\']';
                        if (!empty($client_name))
                        {
                            echo ",'client':'", $client_name, "'";
                        }
                        echo ",'action':'",$config['action'], "'";
                        echo ",'widget_id': '".$widget_id."'";
                    echo '}, \'', $widget_id, '\');return false;" href="#"></a>';
            }
            
            echo '</div>';
        }
        ?>
    </div>
    <?php
    if (!empty($top_actions))
    {
        echo '<div class="box-nav box-nav-margins2">';
            foreach ($top_actions as $action_name => $action_item)
            {
                echo '<div class="btn1 ', $action_item['icon'],'"';
                if (!empty($action_url))
                {
                    echo ' onclick="grid_request(\'', $action_url, '\',';
                    echo '{';
                        echo '\'key\':[\'', implode("','", $key_fields), '\']';
                        if (!empty($client_name))
                        {
                            echo ",'client':'", $client_name, "'";
                        }
                        echo ",'action':'", $action_name, "'";
                    echo '}, \'', $widget_id, '\')"';
                }
                echo '>';
                    echo '<span>', $action_item['title'], '</span><b></b>';
                echo '</div>';
            }
            echo '<div class="l"></div><div class="r"></div>';
        echo '</div>';
    }
    ?>
    <div id="<?php echo $widget_id; ?>" class="gridbox gridbox_orange" style="width:100%;height:100%;background-color:white;overflow:hidden"></div>
    <div id="<?php echo $widget_id; ?>_paging_area" class="dhx_toolbar_base_orange"></div>
</div>
<script>
<?php
$widget_data = array(
    'load_url' => $data_url,
    'id' => $widget_id,
    'columns' => $table_columns,
    'items_per_page' => $items_per_page,
);
if (!empty($header2))
{
    $widget_data['header2'] = $header2; 
}
if (!empty($filters))
{
    $widget_data['filters'] = $filters;
}
if (!empty($extra_params))
{
    // default URl params
    $widget_data['extra_params'] = $extra_params;
}
if (!empty($bottom_block))
{
    // HTML block at the bottom of the grid
    $widget_data['bottom_block'] = $bottom_block;
}
if (!empty($default_block))
{
    // default block uses if grid has no items
    $widget_data['default_block'] = $default_block;
}
if (!empty($funcs))
{
    $widget_data['funcs'] = $funcs;
}
if (isset($ajax_data) && $ajax_data !== NULL)
{
    $widget_data['data'] = $ajax_data;
}
if (!empty($load_once))
{
    $widget_data['load_once'] = 1;
}
if (!empty($extra_data))
{
    $widget_data['extra_data'] = $extra_data;
}
?>
$(function() {
    var grid = init_table_widget(<?php echo json_encode($widget_data, JSON_HEX_TAG); ?>);
    <?php if ($load_data) echo 'grid.loadGrid();', PHP_EOL; ?>
});
</script>