<?php
    $widget = Controller_Ajax_User_TrackData::get_actions_pagetitles_table_widget(FALSE,$table_columns);
    $widget->widget_id = 'actions_getpagetitles';
    $widget->extra_params = array(
            'domain_id' => $domain_id,
            'period'    => $period,
            'date'      => $date,
            'table_columns' => $table_columns
        );
    echo $widget->render();
?>