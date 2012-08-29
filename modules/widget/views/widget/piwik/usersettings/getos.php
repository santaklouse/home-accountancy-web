<?php
    $widget = Controller_Ajax_User_TrackData::get_resolution_table_widget(FALSE);
    $widget->widget_id = 'user_settings_os';
    $widget->table_title = __('Operation System');
    $widget->extra_params = array(
            'domain_id' => $domain_id,
            'period'    => $period,
            'date'      => $date,
            'table_name' => 'user_settings_os'
        );
    echo $widget->render();
?>