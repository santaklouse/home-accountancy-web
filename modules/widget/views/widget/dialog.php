<?php
/*
Requires:
$columns - array of field names
$fields - array of field's data with key = field name
 */
if (empty($fields) || empty($columns))
{
    return;
}
if (!empty($is_translations))
{
    echo '<div class="languages">';
    foreach ($languages as $language)
    {
        echo '<div class="language-item ', ($language['id'] == $language_id) ? ' active' : '', '"';
        echo ' onclick="show_translations(this, ', $language['id'], ')">';
            echo Tpl::image('img/lang/'.$language['name'].'.gif', array('title' => $language['full_name'], 'width' => 14, 'height' => 11));
            echo '<div class="language-label">', $language['name'] , '</div>';
        echo '</div>';
    }
        echo '<div style="clear:both"></div>';
    echo '</div>';
}
?>
<div class="account_edit">
    <?php
    $items_count = count($columns);
    $is_two_columns = $items_count > 8;
    if ($is_two_columns)
    {
        echo '<div class="col1">';
    }
    $item_number = 0;
    foreach ($columns as $field)
    {
        $item = array();
        if (isset($fields[$field]))
        {
            $item += $fields[$field];
        }
        $value = '';
        if (isset($item['translations']))
        {
            if (isset($item['translations']['items']) 
                && array_key_exists($item['translations']['field'],$data))
            {
                $value = $data[$item['translations']['field']];
            }
        }
        else if (isset($data[$field]))
        {
            $value = $data[$field];
        }
        $item = array(
            'field' => $field,
            'value' => $value,
        ) + $item;
        echo Widget::factory('input', $item)->render();

        if ($is_two_columns && round($items_count/2.) == ++$item_number)
        {
            echo '</div><div class="col2">';
        }
    }
    if ($is_two_columns)
    {
        echo '</div>';
    }
    if (isset($is_tinymce) && $is_tinymce)
    {
        echo '<script type="text/javascript">setTimeout("init_tinymce()", 200);</script>';
    }
    if (isset($script))
    {
        echo '<script type="text/javascript">'.$script.'</script>';
    }
    ?>
</div>