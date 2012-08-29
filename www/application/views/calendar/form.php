<?php defined('SYSPATH') or die('No direct script access.');

$form = new Active_Form($model);
echo $form->begin(
    array('action' => 'calendar/update')
);
echo $form->error('');
//var_dump($model->all_errors());
echo '<div class="row">';
    echo $form->label('type_id');
    echo $form->error('type_id');
    echo $form->select(
        'type_id',
        Model_Calendar::types()
    );
echo '</div>';

echo '<div class="row">';
    echo $form->label('notes');
    echo $form->error('notes');
    echo $form->textarea('notes');
echo '</div>';

echo $form->hidden_field('class');
try
{
    echo $form->hidden_field('corporate_tree_id');
}
catch(Exception $e)
{
    echo Form::hidden('Calendar[corporate_tree_id]',$parent_id);
}
echo $form->hidden_field('created_at');
echo $form->hidden_field('id');

echo $form->end();