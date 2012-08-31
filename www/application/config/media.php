<?php defined('SYSPATH') or die('No direct script access.');

return array(
    'core' => array(
        'static_uri' => 'uri',
        'uri' => 'media/',
        'path' => DOCROOT.'media'.DIRECTORY_SEPARATOR,
        'coffeescript' => array(
            'source_path' => DOCROOT.'coffee_scripts'.DIRECTORY_SEPARATOR,
            'dest_path' => DOCROOT.'media'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR
        ),
        'less' => array(
            'source' => DOCROOT.'coffee_scripts'.DIRECTORY_SEPARATOR,
            'dest' => DOCROOT.'media'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR
        ),
    ),
    'default' => array(
        'css' => array(
            'bootstrap/bootstrap' => '',
            'bootstrap/bootstrap-responsive' => '',
            'main' => '',
            'jquery/jquery-ui-1.8.16.clean' => '',
            'jquery/jquery-ui-1.8.16.custom' => '',
        ),
        'js' => array(
            'jquery/jquery.min',
            'jquery/jquery-ui-1.8.16.custom.min',
            'jquery/jquery.form.plugin',
            'bootstrap/bootstrap.min',
            array(
                'name' => 'ui_lib',
                'files' => array(
                    'lib/pseudo_ajax_load_progress',
                    'lib/inline_alert',
                ),
            ),
            array(
                'name' => 'libs',
                'files' => array(
                    'lib/loading_icon',
                    'lib/live_dialog',
                    'lib/pseudo_dialog',
                )
            ),
        ),
    ),
);

