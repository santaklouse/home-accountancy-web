<?php defined('SYSPATH') or die('No direct script access.');

class Payment_Authorize extends Payment {

    // Login ID of authorize.net account
    public $login;

    // Secret key from authorize.net account
    public $secret;

    public function __construct()
    {
        parent::__construct();

        // Some default values of the class
        $this->gateway_url = 'https://secure.authorize.net/gateway/transact.dll';

        // Populate $fields array with a few default
        $this->_params = array(
            'name' => 'x_description',
            'amount' => 'x_amount',
            //'currency_code' => '',
            'customer_id' => 'x_cust_id',
            'invoice_number' => 'x_invoice_num',
        );
        $this->_ipn_params = array(
            'customer_id' => 'x_cust_id',
            'invoice_number' => 'x_invoice_num',
        );

        $this->set(array(
            'x_version' => '3.0',
            'x_show_form' => 'PAYMENT_FORM',
            'x_relay_response' => 'TRUE',
        ));
        $this->login = $this->_config['login'];
        $this->secret = $this->_config['secret_key'];

        $route = Route::get('payment');
        $this->set(array(
            'x_receipt_link_url' => URL::site($route->uri(array(
                'gateway' => $this->get_name(),
                'arg1' => 'success',
            ) + $route->get_defaults()), TRUE),
            'x_relay_url' => URL::site($route->uri(array(
                'gateway' => $this->get_name(),
            ) + $route->get_defaults()), TRUE),
        ));
    }

    public function enable_test_mode()
    {
        $this->test_mode = TRUE;
        $this->set('x_test_request', 'TRUE');
        $this->gateway_url = 'https://test.authorize.net/gateway/transact.dll';
    }

    public function before_payment()
    {
        $this->set(array(
            'x_login' => $this->login,
            'x_fp_sequence' => $this->get('x_invoice_num'),
            'x_fp_timestamp' => time(),
        ));
        $data = $this->get('x_login') . '^' .
                $this->get('x_invoice_num') . '^' .
                $this->get('x_fp_timestamp') . '^' .
                $this->get('x_amount') . '^';

        $this->set('x_fp_hash', $this->hmac($this->secret, $data));
    }

    public function validate_ipn()
    {
        $invoice = intval($this->ipn_data['x_invoice_num']);
        $pnref = $this->ipn_data['x_trans_id'];
        $amount = doubleval($this->ipn_data['x_amount']);
        $result = intval($this->ipn_data['x_response_code']);
        $respmsg = $this->ipn_data['x_response_reason_text'];

        $md5source = $this->secret . $this->login . $this->ipn_data['x_trans_id'] . $this->ipn_data['x_amount'];
        $md5 = md5($md5source);

        if ($result == '1')
        {
            return true;
        }
        else if ($result != '1')
        {
            $this->error = $respmsg;
            return false;
        }
        else if (strtoupper($md5) != $this->ipn_data['x_md5_hash'])
        {
            $this->error = 'MD5 mismatch';
            return false;
        }
        return false;
    }

    /**
     * RFC 2104 HMAC implementation for php.
     */
    private function hmac ($key, $data)
    {
       $b = 64; // byte length for md5

       if (strlen($key) > $b) {
           $key = pack("H*",md5($key));
       }

       $key  = str_pad($key, $b, chr(0x00));
       $ipad = str_pad('', $b, chr(0x36));
       $opad = str_pad('', $b, chr(0x5c));
       $k_ipad = $key ^ $ipad ;
       $k_opad = $key ^ $opad;

       return md5($k_opad  . pack("H*", md5($k_ipad . $data)));
    }
}
