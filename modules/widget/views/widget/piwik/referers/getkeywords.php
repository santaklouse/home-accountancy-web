<?php
    $widget = Controller_Ajax_User_TrackData::get_keywords_site_table_widget(FALSE,$table_columns);
    $widget->widget_id = 'referers_getkeywords'.rand(0,mktime());
    $widget->extra_params = array(
            'domain_id' => $domain_id,
            'period'    => $period,
            'date'      => $date,
            'table_columns' => $table_columns
        );
    echo $widget->render();
?>