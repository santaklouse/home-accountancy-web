<?php defined('SYSPATH') or die('No direct script access.');
return array(
    'default' => array(
        'referers' => array(
            'getkeywords' => array(
                    'label' => array(
                        'label' => __('Keywords'),
                        'type' => 'text',
                        'hidden' => FALSE
                    ),
                    'nb_uniq_visitors' => array(
                        'label' => __('Unique Visitors'),
                        'type' => 'int',
                        'hidden' => FALSE
                    ),
                    'nb_visits' => array(
                        'label' => __('Visits'),
                        'type' => 'int',
                        'hidden' => FALSE
                    ),
                    'nb_actions' => array(
                        'label' => __('Actions'),
                        'type' => 'int',
                        'hidden' => FALSE
                    ),
                    'max_actions' => array(
                        'label' => __('Maximum Actions'),
                        'type' => 'int',
                        'hidden' => FALSE
                    ),
                    'sum_visit_length' => array(
                        'label' => __('Total time spent'),
                        'type' => 'int',
                        'length' => 20,
                        'hidden' => FALSE
                    ),
                    'bounce_count' => array(
                        'label' => __('Bounced Visits'),
                        'type' => 'int',
                        'hidden' => FALSE
                    )
            ),
            'getsearchengines' => array(
                    'label' => array(
                        'label' => __('Search engine name'),
                        'type' => 'text',
                        'hidden' => FALSE
                    ),
                    'url' => array(
                        'label' => __('URL'),
                        'type' => 'text',
                        'length' => 30 ,
                        'hidden' => FALSE
                    ),
                    'nb_uniq_visitors' => array(
                        'label' => __('Unique Visitors'),
                        'type' => 'int',
                        'hidden' => FALSE
                    ),
                    'nb_visits' => array(
                        'label' => __('Visits'),
                        'type' => 'int',
                        'hidden' => FALSE
                    ),
                    'nb_actions' => array(
                        'label' => __('Actions'),
                        'type' => 'int',
                        'hidden' => FALSE
                    ),
                    'max_actions' => array(
                        'label' => __('Maximum Actions'),
                        'type' => 'int',
                        'hidden' => FALSE
                    ),
                    'sum_visit_length' => array(
                        'label' => __('Total time spent'),
                        'type' => 'int',
                        'length' => 20,
                        'hidden' => FALSE
                    ),
                    'bounce_count' => array(
                        'label' => __('Bounced Visits'),
                        'type' => 'int',
                        'hidden' => FALSE
                    ),
                    'nb_visits_converted' => array(
                        'label' => __('Converted visits'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    )
            ),
            'getwebsites' => array(
                  'label' => array(
                        'label' => __('Website'),
                        'type' => 'text',
                        'hidden' => FALSE
                    ),
                    'nb_uniq_visitors' => array(
                        'label' => __('Unique Visitors'),
                        'type' => 'int',
                        'hidden' => FALSE
                    ),
                    'nb_visits' => array(
                        'label' => __('Visits'),
                        'type' => 'int',
                        'hidden' => FALSE
                    ),
                    'nb_actions' => array(
                        'label' => __('Actions'),
                        'type' => 'int',
                        'hidden' => FALSE
                    ),
                    'bounce_count' => array(
                        'label' => __('Bounced Visits'),
                        'type' => 'int',
                        'hidden' => FALSE
                    )
            )
        ),
        'live' => array(
                'getlastvisitsdetails' => array(
                   'datetime' => array(
                        'label' => __('Date time'),
                        'type' => 'text',
                        'hidden' => FALSE
                    ),
                    'country' => array(
                        'label' => __('Country'),
                        'type' => 'text',
                        'hidden' => FALSE
                    ),
                    'browser' => array(
                        'label' => __('Browser'),
                        'type' => 'text',
                        'hidden' => FALSE
                    ),
                    'platform' => array(
                        'label' => __('Platform'),
                        'type' => 'text',
                        'hidden' => FALSE
                    ),
                    'plugins' => array(
                        'label' => __('Plugins'),
                        'type' => 'text',
                        'hidden' => FALSE
                    ),
                    'ip' => array(
                        'label' => __('Ip address'),
                        'type' => 'text',
                        'length' => 18 ,
                        'hidden' => FALSE
                    ),
                    'visitortype' => array(
                        'label' => __('Visitor Type'),
                        'type' => 'text',
                        'hidden' => FALSE
                    ),
                    'actions' => array(
                        'label' => __('Actions'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    )
                )
        ),
        'actions'=> array(
            'getpageurls' => array(
                'label' => array(
                        'label' => __('Page'),
                        'type' => 'text',
                        'hidden' => FALSE
                ),
               'url' => array(
                        'label' => __('Url'),
                        'type' => 'text',
                        'length' => 40,
                        'hidden' => FALSE
                ),
                'nb_visits' => array(
                        'label' => __('Visits'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'nb_uniq_visitors' => array(
                        'label' => __('Unique Visits'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'nb_hits' => array(
                        'label' => __('Visits hits'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'sum_time_spent' => array(
                        'label' => __('Time Spent'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'entry_nb_uniq_visitors' => array(
                        'label' => __('Entry of unique visits'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'entry_nb_visits' => array(
                        'label' => __('Entry of visits'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'entry_nb_actions' => array(
                        'label' => __('Entry of actions'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'entry_sum_visit_length' => array(
                        'label' => __('Entry spent time'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'entry_bounce_count' => array(
                        'label' => __('Entry of bound visits'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'exit_nb_uniq_visitors' => array(
                        'label' => __('Ended of unique visits'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'exit_nb_visits' => array(
                        'label' => __('Ended of visits'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'avg_time_on_page' => array(
                        'label' => __(' Average time'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'bounce_rate' => array(
                        'label' => __('Bounce Rate'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'exit_rate' => array(
                        'label' => __('Exit Rate'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    )
            ),
            'getpagetitles' => array(
                'label' => array(
                        'label' => __('Page title'),
                        'type' => 'text',
                        'hidden' => FALSE
                ),
                'nb_visits' => array(
                        'label' => __('Visits'),
                        'type' => 'int',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'nb_uniq_visitors' => array(
                        'label' => __('Unique Visits'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'nb_hits' => array(
                        'label' => __('Visits hits'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'sum_time_spent' => array(
                        'label' => __('Time Spent'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'entry_nb_uniq_visitors' => array(
                        'label' => __('Entry of unique visits'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'entry_nb_visits' => array(
                        'label' => __('Entry of visits'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'entry_nb_actions' => array(
                        'label' => __('Entry of actions'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'entry_sum_visit_length' => array(
                        'label' => __('Entry spent time'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'entry_bounce_count' => array(
                        'label' => __('Entry of bound visits'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'exit_nb_uniq_visitors' => array(
                        'label' => __('Ended of unique visits'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'avg_time_on_page' =>  array(
                        'label' => __('Average time on page'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'exit_nb_visits' => array(
                        'label' => __('Ended of visits'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'bounce_rate' => array(
                        'label' => __('Bounce Rate'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'exit_rate' => array(
                        'label' => __('Exit Rate'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    )
            ),
            'getoutlinks' => array(
                'label' => array(
                        'label' => __('Page title'),
                        'type' => 'text',
                        'hidden' => FALSE
                ),
                'nb_visits' => array(
                        'label' => __('Visits'),
                        'type' => 'int',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'nb_hits' => array(
                        'label' => __('Visits hits'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                 'sum_time_spent' => array(
                        'label' => __('Time Spent'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'entry_nb_visits' => array(
                        'label' => __('Entry of visits'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'entry_nb_actions' => array(
                        'label' => __('Entry of actions'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'entry_sum_visit_length' => array(
                        'label' => __('Entry spent time'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'entry_bounce_count' => array(
                        'label' => __('Entry Bounce count'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
                'exit_rate' => array(
                        'label' => __('Exit Rate'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    )
            )
        ),
        'usersettings' => array(
            'getresolution' => array(
                'label' => array(
                        'label' => __('Page title'),
                        'type' => 'text',
                        'hidden' => FALSE
                 ),
                'nb_uniq_visitors' => array(
                        'label' => __('Unique Visits'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                ),
                'nb_visits' => array(
                        'label' => __('Visits'),
                        'type' => 'int',
                        'length' => 10 ,
                        'hidden' => FALSE
                 ),
                 'nb_actions' => array(
                        'label' => __('Actions'),
                        'type' => 'int',
                        'hidden' => FALSE
                    ),
                  'max_actions' => array(
                        'label' => __('Max Actions'),
                        'type' => 'int',
                        'hidden' => FALSE
                    ),
                  'bounce_count' => array(
                        'label' => __('Bounced Visits'),
                        'type' => 'int',
                        'hidden' => FALSE
                    ),
                  'sum_visit_length' => array(
                        'label' => __('Total time spent'),
                        'type' => 'int',
                        'length' => 20,
                        'hidden' => FALSE
                    ),
                   'nb_visits_converted' => array(
                        'label' => __('Converted visits'),
                        'type' => 'text',
                        'length' => 10 ,
                        'hidden' => FALSE
                    ),
            ),
            
        ),// user settings
    )
);