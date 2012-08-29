<?php defined('SYSPATH') or die('No direct script access.');

return array
(
    'default' => array(
                'grab_params' => array(
                    'utf8' => TRUE,
                    'group_count' => 20,
                    'group_delay' => 0,
                ),
                'max_level' => 2,
                'max_page_links' => array(
                    1 => 100,
                ),
                'max_level_links' => array(
                    1 => 100,
                ),
                'options' => array(
                    'meta' => array(
                        'count' => 100,
                    ),
                    'description' => array(
                        'count' => 100,
                        'types' => array(1 => TRUE, 2 => FALSE, 3 => FALSE),
                    ),
                    'body' => array(
                        'count' => 100,
                        'types' => array(1 => TRUE, 2 => FALSE, 3 => FALSE),
                    ),
                ),
                'internal_links' => FALSE,
                'external_links' => FALSE
            )
);