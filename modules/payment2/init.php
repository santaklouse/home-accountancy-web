<?php defined('SYSPATH') or die('No direct script access.');

Route::set('payment', 'payment(/<controller>(/<action>(/<gateway>(/<arg1>))))')
    ->defaults(array(
        'directory'  => 'payment',
        'controller' => 'live',
        'action'     => 'notifier',
    ));