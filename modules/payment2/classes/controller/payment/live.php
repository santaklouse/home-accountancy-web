<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Payment_Live extends Controller {

    public function action_notifier()
    {
        $method = $this->request->param('gateway', '');
        $action = $this->request->param('arg1', 'notifier');

        $name = ucfirst(strtolower($method));
        if (!Payment::class_exists($name))
        {
            exit('Incorrect payment method');
        }
        $payment = Payment::factory($name);
        $message = $payment->processing($action, $_POST);

        $this->request->redirect(Route::url('billing', array(), TRUE));
    }
}
