<?php defined('SYSPATH') or die('No direct script access.');

class Payment_Paypal extends Payment {

    private $_default_cmd = '_xclick';

    public function __construct()
    {
        parent::__construct();

        $this->gateway_url = 'https://www.paypal.com/cgi-bin/webscr';

        // Defaults
        if ($this->_default_cmd == '_xclick')
        {
            $this->_params = array(
                'name' => 'item_name',
                'amount' => 'amount',
                'currency_code' => 'currency_code',
                'customer_id' => 'custom',
                'invoice_number' => 'item_number',
            );
            $this->_ipn_params = array(
                'customer_id' => 'custom',
                'invoice_number' => 'item_number',
            );
            $this->set(array(
                'cmd' => '_xclick',
                'rm' => '2', // Return method = POST
                'business' => $this->_config['seller'],
            ));
        }
        else if ($this->_default_cmd == '_cart')
        {
            $this->_params = array(
                'name' => 'item_name_1',
                'amount' => 'amount_1',
                'currency_code' => 'currency_code',
                'customer_id' => 'custom',
                'invoice_number' => 'invoice',
            );
            $this->_ipn_params = array(
                'customer_id' => 'custom',
                'invoice_number' => 'invoice',
            );
    
            $this->set(array(
                'cmd' => '_cart',
                'rm' => '2', // Return method = POST
                'upload' => '1',
                'business' => $this->_config['seller'],
                'cpp_headerback_color' => '001a27',
                'shopping_url' => URL::base(TRUE, TRUE),
            ));
        }

        $route = Route::get('payment');
        $this->set(array(
            'return' => URL::site($route->uri(array(
                'gateway' => $this->get_name(),
                'arg1' => 'success',
            ) + $route->get_defaults()), TRUE),
            'cancel_return' => URL::site($route->uri(array(
                'gateway' => $this->get_name(),
                'arg1' => 'failure',
            ) + $route->get_defaults()), TRUE),
            'notify_url' => URL::site($route->uri(array(
                'gateway' => $this->get_name(),
            ) + $route->get_defaults()), TRUE),
        ));
    }

    public function enable_test_mode()
    {
        $this->test_mode = TRUE;
        $this->gateway_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    }

    public function validate_ipn()
    {
        // parse the paypal URL
        $url_parsed = parse_url($this->gateway_url);

        // generate the post string from the _POST vars
        $post_string = '';
        foreach ($this->ipn_data as $field => $value)
        {
            $post_string .= $field .'=' . urlencode(stripslashes($value)) . '&';
        }
        $post_string .= 'cmd=_notify-validate'; // append ipn command

        if (empty($url_parsed['host']))
        {
            $this->errors[] = 'Incorrect gateway host.';
            return FALSE;
        }

        // open the connection to paypal
        $fp = fsockopen($url_parsed['host'], 80, $err_num, $err_str, 30);

        if(!$fp)
        {
            $this->errors[] = "fsockopen error #$err_num: $err_str";
            return FALSE;
        }
        
        // Post the data back to paypal

        fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n");
        fputs($fp, "Host: $url_parsed[host]\r\n");
        fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($fp, "Content-length: " . strlen($post_string) . "\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        fputs($fp, $post_string . "\r\n\r\n");

        $this->ipn_response = '';
        // loop through the response from the server and append to variable
        while(!feof($fp))
        {
            $this->ipn_response .= fgets($fp, 1024);
        }

         fclose($fp); // close connection

        if (eregi("VERIFIED", $this->ipn_response))
        {
            return TRUE;
        }
        $this->errors[] = 'IPN Validation Failed: '.$url_parsed['host'].$url_parsed['path'];
        return FALSE;
    }

    public function check_ipn_status()
    {
        return ($this->ipn_data['payment_status'] == 'Completed');
    }
}
