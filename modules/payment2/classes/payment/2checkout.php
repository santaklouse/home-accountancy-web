<?php defined('SYSPATH') or die('No direct script access.');

class Payment_2checkout extends Payment {

    // Secret word to be used for IPN verification
    public $secret;

    // Initialize the 2CheckOut gateway
    public function __construct()
    {
        parent::__construct();

        // Some default values of the class
        $this->gateway_url = 'https://www.2checkout.com/checkout/purchase';

        $this->_params = array(
            'name' => 'c_name',
            'amount' => 'total',
            'currency_code' => 'tco_currency',
            'customer_id' => 'custom',
            'invoice_number' => 'cart_order_id',
        );
        
        $this->set(array(
            'sid' => $this->_config['vendor_id'],
        ));

        $route = Route::get('payment');
        $this->set(array(
            'x_Receipt_Link_URL' => URL::site($route->uri(array(
                'gateway' => $this->get_name(),
            ) + $route->get_defaults()), TRUE),
        ));
    }

    public function enable_test_mode()
    {
        $this->test_mode = TRUE;
        $this->set('demo', 'Y');
    }

    public function set_secret($word)
    {
        if (!empty($word))
        {
            $this->secret = $word;
        }
    }

    public function validate_ipn()
    {
        $vendor_number = ($this->ipn_data["vendor_number"] != '') ? $this->ipn_data["vendor_number"] : $this->ipn_data["sid"];
        $order_number = $this->ipn_data["order_number"];
        $order_total = $this->ipn_data["total"];

        // If demo mode, the order number must be forced to 1
        if($this->demo == "Y" || $this->ipn_data['demo'] == 'Y')
        {
            $order_number = "1";
        }

        // Calculate md5 hash as 2co formula: md5(secret_word + vendor_number + order_number + total)
        $key = strtoupper(md5($this->secret . $vendor_number . $order_number . $order_total));

        // verify if the key is accurate
        if($this->ipn_data["key"] == $key || $this->ipn_data["x_MD5_Hash"] == $key)
        {
        	return true;
        }
        else
        {
        	$this->error = "Verification failed: MD5 does not match!";
        	return false;
        }
        return false;
    }
}