<?php
    $widget = Controller_Ajax_User_TrackData::get_resolution_table_widget(FALSE);
    $widget->widget_id = 'user_settings_configuration';
    $widget->table_title = __('Configuration');
    $widget->extra_params = array(
            'domain_id' => $domain_id,
            'period'    => $period,
            'date'      => $date,
            'table_name' => 'user_settings_configuration'
        );
    echo $widget->render();
?>