<?php defined('SYSPATH') or die('No direct script access.');

class Payment_Authorize extends Payment
{

    // Login ID of authorize.net account
    public $login;

    // Secret key from authorize.net account
    public $secret;

    public $ipn_data = array();

    protected $required_fields = array(
        'transaction_id' => 'item_number',
        'user_id' => 'x_cust_id',
        'item_name' => 'x_description',
    );

    public function __get($name)
    {
        if (isset($this->required_fields[$name]))
        {
            return Arr::get($this->data, $this->required_fields[$name], NULL);
        }
        elseif (strstr($name, 'x_') !== FALSE)
        {
            return Arr::get($this->data, $name);
        }
        return Arr::get($this->data, 'x_'.$name);
    }

    public function __set($name, $value)
    {
        if (is_array($value))
        {
            return;
        }
        elseif (isset($this->required_fields[$name]))
        {
            $this->data[$this->required_fields[$name]] = $value;
            return;
        }
        elseif (strstr($name, 'x_') !== FALSE)
        {
            $this->data[$name] = $value;
            return;
        }
        $this->data['x_'.$name] = $value;
    }

    public static function modes()
    {
        return array(
            self::MODE_DEVELOPMENT => 'sandbox',
            self::MODE_LIVE => 'live'
        );
    }

    public function check_ipn_status()
    {
        return (Arr::get($this->ipn_data, 'x_response_code') == 1);
    }

    public function processing($action, $data)
    {
        $message = 'Unknown error.';
        $operation_status = self::STATUS_WAITING;

        $this->set_ipn_data($data);

        $invoice_number = (int)Arr::get($data, 'x_invoice_num');
//        $user_id = (int)Arr::get($data, 'cust_id'); // ?

        switch ($action)
        {
            case 'notification':
                if ($this->validate_ipn())
                {
                    if ($this->check_ipn_status())
                    {
                        $message = 'IPN check status is successful.';
                        $operation_status = self::STATUS_FINISHED;
                    }
                    else
                    {
                        $message = 'IPN check status is bad.';
                    }
                }
                else
                {
                    $message = implode("\n", $this->get_errors());
                }
                break;
            case 'success':
                Flash::set(
                    'notice',
                    mb_ucfirst(__('thankyou')).' '.
                    mb_ucfirst(__('you_new_subscription_will_begin_tomorrow'))
                );
                Request::initial()->redirect('billing/', 302);
                return;
        }

        if ($message && $operation_status !== self::STATUS_WAITING)
        {
            $response_data = array(
                'mode' => Arr::get(self::modes(), $this->mode),
                'message' => $message,
                'ipn_data' => $_POST,
                'ipn_response' => $this->get_ipn_data(),
            );
            $payment_log = new Model_Payment_System_Log(array(
                'transaction_id' => $invoice_number,
                'type_id' => Model_Payment_System_Log::TYPE_RESPONSE,
                'content' => json_encode($response_data),
            ));
            $payment_log->save();

            if ($operation_status == self::STATUS_FINISHED)
            {
                $this->finish($invoice_number);
            }
            Flash::set(
                'notice',
                mb_ucfirst(__('thankyou')).' '.
                mb_ucfirst(__('you_new_subscription_will_begin_tomorrow'))
            );
            echo View::factory('payment/authorize_thankyou', array(
                'back_url' => $this->config['back_url'])
            )->render();
            exit;
        }
        if ($action == 'notifier')
        {
            exit($message);
        }
        return $message;
    }

    protected function set_fields()
    {
        $modes = self::modes();
        $mode = $modes[$this->mode];

        $this->payment_server_url = Arr::get(
            $this->config,
            'url'
        );

        //invoice or other unique number
        $unique_code = time();

        //The merchant assigned invoice number for the transaction
        $this->x_invoice_num = 'order_' . $unique_code;

        $this->x_exp_date = "1220";

        $this->x_version = '3.1';
        $this->x_show_form = 'PAYMENT_FORM';
        $this->x_relay_response = 'TRUE';

        $this->login = Arr::get($this->config, 'login');
        $this->secret = Arr::get($this->config, 'secret_key');

        $route = Route::get('payment');
        $route_params = array(
            'controller' => 'notifier',
            'action' => 'index',
            'payment_system' => 'authorize',
        );
        $this->data['x_receipt_link_url'] = URL::site(
            $route->uri($route_params + array('arg1' => 'success')),
            TRUE,
            TRUE
        );
        $this->data['x_relay_url'] = URL::site(
            $route->uri($route_params),
            TRUE,
            TRUE
        );

        if ($this->mode == self::MODE_DEVELOPMENT)
        {
            $this->x_test_request = 'TRUE';
        }
    }

    public function before_payment()
    {
        $this->update_fields(array(
            'x_login' => $this->login,
            'x_fp_sequence' => $this->x_invoice_num,
        ));

        $this->x_fp_timestamp = time();
        $data = $this->x_login . '^' .
                $this->x_invoice_num . '^' .
                $this->x_fp_timestamp . '^' .
                $this->x_amount . '^';

        $this->x_fp_hash = hash_hmac('md5', $data, $this->secret);
        if ( ! $this->cust_id)
            throw new Exception("cust_id is required field.");
        $this->set_user_billing_info($this->cust_id);
    }

    public function validate_ipn()
    {
        $ipn_data = $this->get_ipn_data();
        $invoice = Arr::get($ipn_data, 'x_invoice_num');
        if ( ! $invoice)
        {
            die('Bad invoice number');
        }

        $result = Arr::get($ipn_data, 'x_response_code');

        if ($result == 1)
        {
            return true;
        }
        elseif ($result != '1')
        {
            $this->error = $respmsg;
            return false;
        }
        elseif (strtoupper($md5) != $this->ipn_data['x_md5_hash'])
        {
            $this->error = 'MD5 mismatch';
            return false;
        }
        return false;
    }

    protected function set_user_billing_info($user_id)
    {
        $model = Model_User::find($user_id);
        $fields = array(
            //name in model => name in Authorize module
            'first_name' => 'x_first_name',
            'last_name' => 'x_last_name',
            'city' => 'x_city',
            'email' => 'x_email',
            'zip_code' => 'x_zip',
            'address' => 'x_address',
        );
        foreach($fields as $model_name => $module_name)
        {
            $this->{$module_name} = $model->{$model_name};
        }
        //set country name
        if ($model->country_id)
        {
            $this->x_country = Model_Country::find($model->country_id)->name;
        }
    }

}
