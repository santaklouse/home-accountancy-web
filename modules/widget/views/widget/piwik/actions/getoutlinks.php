<?php
    $widget = Controller_Ajax_User_TrackData::get_actions_outlinks_table_widget(FALSE,$table_columns);
    $widget->widget_id = 'actions_getoutlinks';
    $widget->extra_params = array(
            'domain_id' => $domain_id,
            'period'    => $period,
            'date'      => $date,
            'table_columns' => $table_columns
        );
    echo $widget->render();
?>