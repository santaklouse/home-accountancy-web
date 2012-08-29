<?php

class Controller_Payment_Notifier extends Controller
{

    public function action_index()
    {
        $payment_system = $this->request->param('payment_system');
        $action = $this->request->param('arg1');
        if ( ! $action)
        {
            $action = 'notification';
        }

        $klass = 'Payment_'.$payment_system;
        if ( ! $payment_system || ! class_exists($klass))
        {
            die("Invalid payment system");
        }
        $reflection = new ReflectionClass($klass);
        $payment = $reflection->newInstance();

        $message = $payment->processing($action, $_POST);
    }

}
