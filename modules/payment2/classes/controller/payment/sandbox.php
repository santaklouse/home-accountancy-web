<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Payment_Sandbox extends Controller {

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
        $payment->enable_test_mode();
        $message = $payment->processing($action, $_POST);
        
        $this->request->redirect(Route::url('billing', array(), TRUE));
    }
/*
    public function action_checkout()
    {
        $method = $this->request->param('gateway');

        $user_id = 1;
        $package_id = 1;
        $currency_code3 = 'USD';
        $currency_rate = 1;
        $credits = 4;
        $amount = $credits * $currency_rate;

        // ADD PAYMENT RECORD

        $payment = Payment::factory('Paypal');
        $payment->enable_test_mode();
        $payment->set('customer_id', $user_id);
        $payment->set('invoice_number', 20); // transaction_id

        $payment->set('currency_code', $currency_code3);
        $payment->set('name', '4 credits');
        $payment->set('amount', sprintf('%.2f', $amount));
        $html_result = $payment->render();

        echo '<table width="100%" cellpadding="0" cellspacing="0">'.
            '<tr>'.
                '<td valign="middle" align="center" height="50">'.
                    'Please wait, your order is being processed and <br />'.
                    'you will be redirected to the payment website.'.
                '</td>'.
            '</tr>'.
            '</table>';
        echo $html_result;
    }
*/
}
