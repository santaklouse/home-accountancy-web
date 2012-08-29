<?php defined('SYSPATH') or die('No direct script access.');

Route::set('payment', 'payment(/<controller>(/<action>(/<payment_system>(/<arg1>))))')
    ->defaults(array(
        'directory'  => 'payment',
        'controller' => 'notifier',
        'action'     => 'index',
        'payment_system' => '',
        'arg1' => '',
    ));
