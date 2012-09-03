<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Calendar extends Controller_Core {

    protected $check_access = false;

    public function before()
    {
        parent::before();
        $this->current_user = Auth::instance()->current_user();
    }

    public static function check_date($unixdate)
    {
        $year = date('Y', $unixdate);
        $mouth = date('m', $unixdate);
        $day = date('d', $unixdate);

        if ( ! checkdate($mouth, $day, $year))
            return FALSE;

        if (($year > 2000 && $year < 2099) &&
            ($mouth > 0 && $mouth < 13) &&
            ($day > 0 && $day < 32))
            return TRUE;

        return FALSE;
    }

    public function action_index()
    {
        $date_start = Arr::get($_REQUEST, 'date') ?: NULL;
        $this->view->date_start = date('Y-m-d', strtotime('now'));
        if ( $date_start && self::check_date(strtotime($date_start)))
        {
            $this->view->date_start = $date_start;
        }

//        $model =  Model_Calendar_Event::find_all(array(
//                'corporate_tree_id' => $this->parent_id,
//                'created_at' =>  array(
//                    'between',
//                    date('Y-m-d',Date::start_of_month($this->view->date_start)),
//                    date('Y-m-d',Date::end_of_month($this->view->date_start))
//                ),
//        ));
//
//        $model_specials = Model_Calendar_Special_Day::find_all(array(
//            'corporate_user_id' => $parent->corporate_user_id,
//        ));
//
//        $due_to = Model_Corporate_Tree::due_to_collection(
//            $this->current_user,
//            $this->branch_id,
//            $this->parent_id
//        );
//
//        $events = array();
//        foreach ($model->records as $event)
//        {
//            $events[$event->created_at][] = $event;
//        }
//
//        $special_days = array();
//        foreach ($model_specials->records as $special_day)
//        {
//            $special_days[$special_day->created_at][] = $special_day;
//        }

//        $this->view->due_to = $due_to;
//        $this->view->events = $events;
//        $this->view->special_days = $special_days;

        $this->render_partial();
    }

    public function action_new()
    {
        $model = new Model_Calendar(
            array(
                'created_at' => Arr::get($_REQUEST, 'date'),
                'corporate_tree_id' => $this->parent_id,
                'notes' => NULL,
                'type_id' => NULL,
                'class' => NULL,
            )
        );

        $this->render_partial(
            'calendar/form',
            array(
                'model' => $model,
                'parent_id' => $this->parent_id,
            )
        );
    }

    public function action_update()
    {
        $fields = array(
            'corporate_tree_id',
            'created_at',
            'notes',
            'type_id',
            'class',
            'id'
        );

        $params = array();

        foreach($fields as $field)
        {
            $params[$field] = Arr::path($_REQUEST, 'Calendar.' . $field);
        }

        $parent = Model_Corporate_Tree::find($this->parent_id);
        $params['corporate_owner_id'] = $parent->corporate_user_id;
        if ( ! $this->branch_id)
        {
            $params['corporate_user_id'] = $parent->corporate_user_id;
        }
        else
        {
            $owner_id = $parent->owner->user->id;
            $corporate_user = Model_Corporate_User::find(array(
                'user_id' => $this->current_user->id,
                'owner_id' => $owner_id,
                'state' => Model_Corporate_User::STATE_ENABLED,
            ));
            $params['corporate_user_id'] = $corporate_user->id;
        }

        $model = new Model_Calendar($params);

        if ( ! $model->save())
        {
            $this->render_partial(
                'calendar/form',
                array(
                    'model' => $model,
                    'parent_id' => $this->parent_id,
                )
            );
            return;
        }

        $this->render_nothing();
    }

    public function action_edit()
    {
        $model = Model_Calendar::find(array(
            'id' => $this->request->param('id'),
            'class' => Arr::get($_REQUEST, 'class'),
        ));

        $this->render_partial(
            'calendar/form',
            array(
                'model' => $model,
                'parent_id' => $this->parent_id,
            )
        );
    }

}