<?php
if (!empty($hidden))
{
    echo Form::hidden($field, $value);
    return;
}
if (!empty($label))
{
    echo '<div class="';
    if (!empty($icon))
    {
        echo 'field-icon-name ', $icon;
    }
    else
    {
        echo 'field-name'; 
    }
    if (!empty($disabled))
    {
        echo ' field-readonly';
    }
    echo '">';
    if (!empty($required))
    {
        echo '*';
    }
    echo $label;
    if (isset($translations))
    {
        echo ' (ML)';
    }
    echo ':</div>';
}
if (!isset($type))
{
    $type = 'text';
}
if ($type == 'image' && isset($value) && ($value != ''))
{
    echo '<img src="'.$value.'" width="50" height="50" />';

    echo '<br/><div class="field-name">';
    echo 'Change image:';
    echo '</div>';
}
if ($type != 'checkset')
{
    echo '<div class="input-container">';
}
$attributes = array();
if (!empty($disabled))
{
    $attributes['disabled'] = 'disabled';
}
else if (!empty($readonly))
{
    echo Form::hidden($field, $value);
    $attributes['disabled'] = 'disabled';
}
if ($type == 'json')
{
    
}
if (isset($translations))
{
    if ($translations['field'] == $field)
    {
        echo Form::hidden($field, $value, $attributes);
    }
    foreach ($languages as $id => $language)
    {
        $text = '';
        if (isset($translations['items'][$id][$value]))
        {
            $text = $translations['items'][$id][$value];
        }
        else if (isset($translations['items'][$default_language_id][$value]))
        {
            $text = $translations['items'][$default_language_id][$value];
        }
        if ($id != $language_id)
        {
            $attributes['class'] = 'hidden';
        }
        else
        {
            $attributes['class'] = '';
        }
        if ($type == 'textarea')
        {
            echo Form::textarea('translations['.$id.']['.$field.']', 
                $text, $attributes + array('cols' => 40,'rows' => 5));
        }
        else if ($type == 'html')
        {
            $attributes['class'] = (isset($attributes['class'])) ? $attributes['class'] . ' textarea-html' : 'textarea-html';
            echo Form::textarea('translations['.$id.']['.$field.']', 
                $text, $attributes + array(
                    'id' => 'tinymce'.  md5(microtime() . mt_rand(1,1000)),
                    'cols' => 40,
                    'rows' => 5,
                )
            );
        }
        else
        {
            echo Form::input('translations['.$id.']['.$field.']',
                $text, $attributes + array('size' => '40'));
        }
    }
}
else if (isset($enum) && ($type != 'checkset'))
{
    echo Form::select($field, $enum, $value, $attributes);
}
else if ($type == 'checkset')
{
    foreach ($enum as $key => $name)
    {
        $checked = TRUE;
        if (array_key_exists($key,$value))
        {
            $checked = FALSE;
        }
        echo '<label class="checkbox">' . Form::checkbox($field.'['.$key.']', $key, $checked, $attributes). 
                '<span>'.$name .'</span></label><br/>';
    }
}
else if ($type == 'password')
{
    echo Form::password($field, $value, array('size' => '40'));
}
else if ($type == 'date')
{
    echo Form::input($field, $value, $attributes + array('size' => '40', 'class' => 'datepicker'));
}
else if ($type == 'textarea')
{
    echo Form::textarea($field, $value, $attributes + array('class' => 'textarea') + array('cols' => 40,'rows' => 5));
}
else if ($type == 'html')
{
    echo Form::textarea($field, $value, $attributes + array('class' => 'textarea-html','id' => 'tinymce'.  md5(microtime() . mt_rand(1,1000))) + array('cols' => 40,'rows' => 5));
}
else if ($type == 'image')
{
    echo Form::file($field, $attributes);
}
else
{
    echo Form::input($field, $value, $attributes + array('size' => '40'));
}
echo '</div>';