<?php defined('SYSPATH') or die('No direct script access.');

class Payment_PayPal extends Payment
{

    public $ipn_data = array();
    public $ipn_response = array();

    protected $required_fields = array(
        'transaction_id' => 'item_number',
        'user_id' => 'custom',
    );

    public function __get($name)
    {
        if (isset($this->required_fields[$name]))
        {
            return Arr::get($this->data, $this->required_fields[$name], NULL);
        }
        else
        {
            parent::__get($name);
        }
    }

    public function __set($name, $value)
    {
        if (isset($this->required_fields[$name]))
        {
            $this->data[$this->required_fields[$name]] = $value;
        }
        else
        {
            parent::__set($name, $value);
        }
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
        return ($this->ipn_data['payment_status'] == 'Completed');
    }

    public function get_ipn_response()
    {
        return $this->ipn_response;
    }

    public function processing($action, $data)
    {
        $message = 'Unknown error.';
        $operation_status = self::STATUS_WAITING;

        $this->set_ipn_data($data);

        $invoice_number = (int)Arr::get($data, 'item_number'); // ? Waiting for BD structure
//        $user_id = (int)Arr::get($data, 'customer_id'); // ?

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

            case 'failure':
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
        }
        if ($action == 'notifier')
        {
            exit($message);
        }
        return $message;
    }

    protected function before_payment() {}

    protected function set_fields()
    {
        $modes = self::modes();
        $mode = $modes[$this->mode];
        if ( ! empty($this->config))
        {
            $this->data['business'] = Arr::get(
                $this->config,
                'email'
            );
            $this->payment_server_url = Arr::get(
                $this->config,
                'url'
            );
            $this->data['cmd'] = '_xclick';
            $this->data['no_shipping'] = 1;
            $this->data['rm'] = 2; // Return method = POST
            $this->data['currency_code'] = Arr::get($this->config, 'currency_code', 'USD');

            $route = Route::get('payment');

            $route_params = array(
                'controller' => 'notifier',
                'action' => 'index',
                'payment_system' => 'paypal',
            );
            $this->data['return'] = URL::site(
                $route->uri($route_params + array('arg1' => 'success')),
                TRUE,
                TRUE
            );
            $this->data['cancel_return'] = URL::site(
                $route->uri($route_params + array('arg1' => 'failure')),
                TRUE,
                TRUE
            );
            $this->data['notify_url'] = URL::site(
                $route->uri($route_params),
                TRUE,
                TRUE
            );
        }
    }

    protected function validate_ipn()
    {
        // parse the paypal URL
        $url_parsed = parse_url($this->payment_server_url);

        // generate the post string from the $_POST vars
        $post_string = 'cmd=_notify-validate&'; // append ipn command
        foreach ($this->ipn_data as $field => $value)
        {
            $post_string .= $field .'=' . urlencode(stripslashes($value)) . '&';
        }

        if (empty($url_parsed['host']))
        {
            $this->errors[] = 'Incorrect gateway host.';
            return FALSE;
        }

        //init connection to PayPal
        $curl = curl_init($this->payment_server_url);
        curl_setopt ($curl, CURLOPT_HEADER, 0);
        curl_setopt ($curl, CURLOPT_POST, 1);
        curl_setopt ($curl, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 1);

        // Send Post data back to paypal
        $this->ipn_response = curl_exec ($curl);
        curl_close ($curl);

        if (strstr($this->ipn_response, "VERIFIED"))
        {
            return TRUE;
        }
        $this->errors[] = 'IPN Validation Failed: '.$url_parsed['host'].$url_parsed['path'];
        return FALSE;
    }

}
