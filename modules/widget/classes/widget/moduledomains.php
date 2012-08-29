<?php defined('SYSPATH') or die('No direct script access.');

class Widget_Moduledomains extends Widget {

    protected $_data = array(
        'module_indetifier' => '',
        'module_id' => 0, 
        'domains_list' => array(), 
        'current_url' => '', 
        'link' => array()
        );

    public function render() {

        $domains = Client::factory('UserUrl')->set(array('module_id' => $this->module_id))->find_by_module();
        if($domains)
        {
            $this->domains_list = $domains->records;
            foreach ($this->domains_list as $key => $item) {
                if ($item['id'] == Input::param('arg1'))
                    $this->current_url = $item['url']['url'];
                $this->link[$key] = Route::url('keyword', array('action' => 'ranking', 'arg1' => $item['id']), TRUE);
            }
        }
        return parent::render();
    }

}
