<?php defined('SYSPATH') or die('No direct script access.');

abstract class Payment {

    const STATUS_FINISHED = 2;
    const STATUS_WAITING = 1;
    const STATUS_FAILED = 0;

    const MODE_DEVELOPMENT = 0;
    const MODE_LIVE = 1;

    public $mode = self::MODE_DEVELOPMENT;
    public $payment_server_url = '';
    protected $errors = array();
    protected $data = array();
    protected $config = array();

    public static function modes(){}

    abstract protected function set_fields();

    abstract protected function before_payment();

    public function set_ipn_data($data)
    {
        $this->ipn_data = $data;
    }

    public function get_ipn_data()
    {
        return $this->ipn_data;
    }

    public function get_fields()
    {
        return $this->data;
    }

    public function update_fields($fields = array())
    {
        $this->data = Arr::merge($this->data, $fields);
    }

    public function __construct($data = array())
    {
        $name = substr(get_called_class(), strlen(get_class()) + 1);

        $this->config = (empty($data))
            ? Kohana::$config->load(strtolower($name))->as_array()
            : $data;

        $this->mode = ( ! Arr::get($this->config, 'test_mode', FALSE))
            ? self::MODE_LIVE
            : self::MODE_DEVELOPMENT;

        $this->set_fields();
    }

    public function __get($name)
    {
        return Arr::get($this->data, $name, NULL);
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function get_errors()
    {
        return $this->errors;
    }

    public function render()
    {
        $this->before_payment();

        return View::factory('payment/form', array(
            'submit_url' => $this->payment_server_url,
            'inputs' => $this->data,
        ))->render();
    }

    protected function finish($transaction_id)
    {
        return Model_Transaction::find($transaction_id)->finish();
    }

}
