<?php defined('SYSPATH') or die('No direct script access.');

class Payment {

    public static $instances = array();

    protected $_name = '';
    protected $_data = array();
    protected $_errors = array();
    protected $_config = array();
    protected $_params = array();
    protected $_ipn_params = array();

    public $test_mode = FALSE;
    public $ipn_response = '';
    public $ipn_data = array();
    public $gateway_url = '';
    
    const WAITING = 0;
    const PROCESSING = 1;
    const FINISHED = 2;
    const CANCELED = 3;
    const FAILED = 4;

    public static function class_exists($name)
    {
        return class_exists(get_class().'_'.$name);
    }
    
    public static function factory($name = NULL)
    {
        $class = get_called_class();
        if ($name === NULL)
        {
            $name = substr($class, strlen(get_class()) + 1);
        }
        if (! isset(self::$instances[$name]))
        {
            $inherit_class = get_class().'_'.$name;
            if (class_exists($inherit_class))
            {
                $class = $inherit_class;
            }
            self::$instances[$name] = new $class($name);
        }
        self::$instances[$name]->_errors = array();
        return self::$instances[$name];
    }

    public function __construct($name = NULL)
    {
        if ($name === NULL)
        {
            $name = substr(get_called_class(), strlen(get_class()) + 1);
        }
        $this->_name = $name;

        $config = Kohana::$config->load(strtolower(get_class()));
        $key = strtolower($this->_name);
        if (isset($config[$key]))
        {
            $this->_config = $config[$key];
        }
    }

    public function get_name()
    {
        return $this->_name;
    }

    public function get($key)
    {
        return isset($this->_data[$key]) ? $this->_data[$key] : NULL;
    }

    public function set($params, $value = NULL)
    {
        if (!empty($params))
        {
            if (!is_array($params))
            {
                $params = array($params => $value);
            }
            foreach ($params as $param => $value)
            {
                if (isset($this->_params[$param]))
                {
                    $this->_data[$this->_params[$param]] = $value;
                }
                else
                {
                    $this->_data[$param] = $value;
                }
            }
        }
        return $this;
    }
    
    public function get_ipn_data()
    {
        return $this->ipn_data;
    }
    
    public function get_ipn_response()
    {
        return $this->ipn_response;
    }

    public function get_errors()
    {
        return $this->_errors;
    }

    public function get_ipn_param($param)
    {
        if (isset($this->_ipn_params[$param]))
        {
            $field = $this->_ipn_params[$param];
            if (isset($this->ipn_data[$field]))
            {
                return $this->ipn_data[$field];
            }
            return FALSE;
        }
        return FALSE;
    }

    public function set_ipn_data($ipn_data)
    {
        $this->ipn_data = $ipn_data;
    }

    public function check_ipn_status()
    {
        return TRUE;
    }

    public function render()
    {
        $this->before_payment();

        return View::factory('payment/form', array(
            'submit_url' => $this->gateway_url,
            'inputs' => $this->_data,
        ))->render();
    }
    
    public function enable_test_mode()
    {
        $this->test_mode = TRUE;
        $route = Route::get('payment');
        $route->set_defaults(array('controller' => 'sandbox') + $route->get_defaults());
    }

    public function processing($action, $data)
    {
        $message = 'Unknown error.';
        $operation_status = self::WAITING;

        ///$this->enable_test_mode();
        $this->set_ipn_data($data);
        $invoice_number = (int)$this->get_ipn_param('invoice_number');
        $user_id = (int)$this->get_ipn_param('customer_id');

        switch ($action)
        {
            case 'notifier':
                if ($this->validate_ipn())
                {
                    if ($this->check_ipn_status())
                    {
                        $message = 'IPN check status is successful.';
                        $operation_status = self::FINISHED;
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
                $message = 'Payment response is successful.';
                $operation_status = self::FINISHED;
                break;
        
            case 'failure':
                $message = 'Payment response is failure.';
                $operation_status = self::FAILED;
                break;
        }
        
        if ($message && $operation_status !== self::WAITING)
        {
            $response_data = array(
                'mode' => $this->test_mode ? 'sandbox' : 'live',
                'message' => $message,
                'ipn_data' => $_POST,
                'ipn_response' => $this->get_ipn_response(),
            );
            Client::factory('PaymentSystemLog')->set(array(
                'transaction_id' => $invoice_number,
                'type_id' => 2, // Response
                'content' => json_encode($response_data, JSON_HEX_TAG),
            ))->create();

            if ($operation_status == self::FINISHED)
            {
                Client::factory('Transaction')->set(array(
                    'id' => $invoice_number,
                ))->finish();
            }
        }
        if ($action == 'notifier')
        {
            exit($message);
        }
        return $message;
    }

    protected function before_payment()
    {
    }

    protected function validate_ipn()
    {
        return FALSE;
    }
}
