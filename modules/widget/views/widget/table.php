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
    </div>
    <?php
    if (isset($top_actions))
    {
        echo '<div class="box-nav box-nav-margins2">';
            foreach ($top_actions as $action_name => $action_item)
            {
                echo '<div class="btn1 ', $action_item['icon'],'"';
                if (!empty($action_url))
                {
                    echo ' onclick="dialog_request(\'', $action_url, '\',';
                    echo '{';
                        echo '\'key\':[\'', implode("','", $key_fields), '\']';
                        if (!empty($client_name))
                        {
                            echo ",'client':'", $client_name, "'";
                        }
                        echo ",'action':'", $action_name, "'";
                    echo '})"';
                }
                echo '>';
                    echo '<span>', $action_item['title'], '</span><b></b>';
                echo '</div>';
            }
            echo '<div class="l"></div><div class="r"></div>';
        echo '</div>';
    }
    ?>
    <div class="box-table box-table-style3">
        <table>
        <tbody>
            <?php
            if (!empty($header_groups))
            {
                echo '<tr class="box-table-header-style1">';
                foreach ($header_groups as $header_group)
                {
                    $columns_count = count($header_group['items']);
                    echo '<th', (($columns_count > 1) ? ' colspan="'.$columns_count.'"' : '') ,'>';
                        echo '<div>', $header_group['title'], '<div class="l"></div><div class="r"></div></div>';
                    echo '</th>';
                    if ($columns_count <= 1)
                    {
                        foreach ($header_group['items'] as $field => $item)
                        {
                            if (isset($fields[$field]))
                            {
                                $fields[$field]['label'] = '';
                            }
                        }
                    }
                }
                if (!empty($row_actions))
                {
                    echo '<th><div>', __('Actions'), '<div class="l"></div><div class="r"></div></div></th>';
                }
                echo '</tr>';
            }
            $columns_count = 0;
            echo '<tr class="box-table-header">';
                foreach ($columns as $field)
                {
                    if (!isset($fields[$field]) || !empty($fields[$field]['hidden']))
                    {
                        continue;
                    }
                    ++$columns_count;

                    echo '<th>';
                    if (isset($fields[$field]['label']))
                    {
                        if (isset($fields[$field]['icon']))
                        {
                            echo '<span class="', $fields[$field]['icon'], '"></span><br />';
                        }
                        echo $fields[$field]['label'];
                    }
                    else
                    {
                        echo $field;
                    }
                    echo '</th>';
                }
                if (!empty($row_actions))
                {
                    $width = count($row_actions) * 24 + 24;
                    if ($width < 72)
                    {
                        $width = 72;
                    }
                    echo '<th width="', $width, '" nowrap="nowrap">', (empty($header_groups) ? __('Actions') : '&nbsp;'), '</th>';
                }
            echo '</tr>';

            if (empty($groups))
            {
                $groups = array(
                    array(
                        'items' => &$data,
                    ),
                );
            }
            else
            {
                foreach ($groups as &$group)
                {
                    $group['items'] = array();
                    if (!empty($group['key']))
                    {
                        foreach ($data as $id => &$row)
                        {
                            $is_add = TRUE;
                            foreach ($group['key'] as $field => $value)
                            {
                                if (!isset($row[$field]) || $row[$field] != $value)
                                {
                                    $is_add = FALSE;
                                    break;
                                }
                            }
                            if ($is_add)
                            {
                                $group['items'][$id] = $row;
                            }
                        }
                    }
                }
            }
            if (!empty($row_actions))
            {
                ++$columns_count;
            }
            $not_empty = TRUE;
            if (count($groups) <= 1)
            {
                $group = current($groups);
                if (empty($group['items']))
                {
                    $not_empty = FALSE;
                    echo '<tr class="row1">';
                        echo '<td colspan="', $columns_count, '">', __('No items.'), '</td>';
                    echo '</tr>';
                }
            }
            if ($not_empty)
            {
                foreach ($groups as &$group)
                {
                    $show_group = isset($group['key'], $group['title']); 
                    if ($show_group)
                    {
                        echo '<tr class="row1">';
                         
                        if (!empty($group['row_actions']))
                        {
                            echo '<td colspan="', $columns_count-1, '"><span class="collapse"></span>', $group['title'], '</td>';
                            echo '<td class="col1">';
                            foreach ($group['row_actions'] as $action_name => $action_item)
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
                                echo '<div class="table-icon ', $action_item['icon'],'"';
                                $url = Arr::get($action_item, 'action_url', $action_url);
                                if (!empty($url))
                                {
                                    echo ' onclick="dialog_request(\'', $url, '\',';
                                    echo '{';
                                    if (!empty($group['key']))
                                    {
                                        echo "'key':['", implode("','", array_keys($group['key'])), "']";
                                        echo ",'val':['", implode("','", $group['key']), "']";
                                        if (!empty($client_name))
                                        {
                                            echo ",'client':'", $client_name, "'";
                                        }
                                        echo ",'action':'", $action_name, "'";
                                    }
                                    echo '})"';
                                }
                                if (isset($action_item['title']))
                                {
                                    echo ' title="', $action_item['title'], '"';
                                }
                                echo '>';
                                echo '</div>';
                            }
                            echo '</td>';
                        }
                        else 
                        {
                            echo '<td colspan="', $columns_count, '"><span class="collapse"></span>', $group['title'], '</td>';
                        }    
                        echo '</tr>';
                    }
                    foreach ($group['items'] as $id => $row)
                    {
                        echo '<tr>';
                        $coulumn_number = 0;
                        $formated_row = $row;
                        if (!empty($formated_data[$id]))
                        {
                            $formated_row = $formated_data[$id] + $row;
                        }
                        foreach ($columns as $field)
                        {
                            $item = Arr::get($fields, $field, array());
                            if (!empty($item['hidden']))
                            {
                                continue;
                            }
                            ++$coulumn_number;
                            $value = NULL;
                            $field_class = FALSE;
                            $field_width = FALSE;
                            if (isset($formated_row[$field]))
                            {
                                $value = $formated_row[$field];
                            }
                            if (isset($item['type']))
                            {
                                switch ($item['type'])
                                {
                                    case 'int':
                                        $field_width = 8;
                                        break;
                                    case 'float':
                                        $field_width = 11;
                                        break;
                                    case 'text':
                                    case 'textarea':
                                        $field_class = 'text-left';
                                        break;
                                    case 'date':
                                        $field_width = 8;
                                        break;
                                    case 'datetime':
                                        $field_width = 17;
                                        break;
                                    case 'checkbox':
                                        $field_width = 5;
                                        $value = '<input type="checkbox" value="1" />';
                                        break;
                                }
                                if (!empty($item['length']))
                                {
                                    $field_width = $item['length'];
                                }
                            }
                            echo '<td';
                            if ($field_width)
                            {
                                echo ' width="', ($field_width * 7), '"';
                            }
                            if ($field_class)
                            {
                                if ($show_group && $coulumn_number == 1)
                                {
                                    $field_class = 'text-child';
                                }
                                echo ' class="', $field_class, '"';
                            }
                            echo '>', ($value ? $value : '-'), '</td>';
                        }
                        if (!empty($row_actions))
                        {
                            echo '<td class="col1">';
                            foreach ($row_actions as $action_name => $action_item)
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
                                echo '<div class="table-icon ', $action_item['icon'],'"';
                                $url = Arr::get($action_item, 'action_url', $action_url);
                                if (!empty($url))
                                {
                                    echo ' onclick="dialog_request(\'', $url, '\',';
                                    echo '{';
                                    if (!empty($key_fields))
                                    {
                                        echo "'key':['", implode("','", $key_fields), "']";
                                        $row_keys = array();
                                        foreach ($key_fields as $row_key)
                                        {
                                            $row_keys[] = Arr::get($row, $row_key, '');
                                        }
                                        echo ",'val':['", implode("','", $row_keys), "']";
                                        if (!empty($client_name))
                                        {
                                            echo ",'client':'", $client_name, "'";
                                        }
                                        echo ",'action':'", $action_name, "'";
                                    }
                                    echo '})"';
                                }
                                if (isset($action_item['title']))
                                {
                                    echo ' title="', $action_item['title'], '"';
                                }
                                echo '>';
                                echo '</div>';
                            }
                            echo '</td>';
                        }
                        echo '</tr>';
                    }
                }
            }
            ?>
        </tbody>
        </table>
        <?php
        if (isset($total_items))
        {
            $config = array(
                'total_items' => $total_items,
            );
            if (isset($items_per_page))
            {
                $config['items_per_page'] = $items_per_page;
            }
            echo Pagination::factory($config, 'table')->render();
        }
        if (!empty($filter))
        {
        ?>
        <div class="box-table-filter">
            <a href="#" class="save" onclick="return false;"></a>
            <?php echo __('Filter:');?>
            <input name="" type="text" class="inp" />
            <input name="" type="button" value="Search" class="btn" />
            &nbsp;
            <?php echo __('Records per page:');?>
            <select name="" class="sel">
                <option>5</option>
            </select>
        </div>
        <?php
        }
        ?>
    </div>
</div>
