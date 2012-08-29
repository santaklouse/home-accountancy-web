<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax_Widget_Grid extends Controller_Template_Ajax_Common {

    public function action_action()
    {
        $allow_actions = array('add', 'edit', 'delete');
        $action_data = Input::get();
        $input_data = Input::post();

        $widget = Widget_Dialog::factory();
        if ( ! $widget->init_client($action_data, $allow_actions, NULL))
        {
            return FALSE;
        }
        if (Arr::get($input_data, 'action_type') == 'save')
        {
            $errors = $widget->default_process($input_data);
            if (empty($errors))
            {
                $this->ajax->refresh = TRUE;
                return TRUE;
            }
            return $this->add_error($errors);
        }
        $this->ajax->set($widget->get_response());
        return TRUE;
    }

    public function action_data()
    {
        $widget = Widget_Grid::factory();
        $this->ajax->set($widget->get_ajax_page());
    }
}