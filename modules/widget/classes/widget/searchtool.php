<?php defined('SYSPATH') or die('No direct script access.');

class Widget_Searchtool extends Widget {

    protected $_data = array(
        'user' => array(),
        'latest_search_visible' => TRUE,
        'current_tab' => FALSE,
        'found' => TRUE,
    );

    /*
     * Helper function to build and render Quick Search (tabs with Domain,Backlinks,Whois,Keywords search)
     * @param void
     * @return void
     */
    public function render($file = NULL)
    {
        $this->user = Request::initial()->user;
        $search_str = '';
        $keyword_str = '';
        $modules_data = Helper_Module::get_modules_data('full');
        $this->items = $modules_data['items'];
//        if (isset($this->tabs) == 'full')
        if (1)
        {
            $this->tabs = array(
                'searchtool/domain' => 64,
//                'searchtool/backlinks' => 67,
//                'searchtool/whois' => 69,
                'searchtool/keyword' => 70,
                'searchtool/bulkdomain' => 72
            );
        }
        else
        {
            $this->tabs = $modules_data['search_tools'];
            if (empty($this->tabs))
            {
                $this->tabs = array();
                $this->items = array();
            }
        }
        if(Request::initial()->action() == 'search')
        {
            $search_str = Input::get('domainurl');
            $keyword_str = Input::get('keyword');
        }

        if(Request::initial()->action() == 'site')
            $search_str = Request::initial()->param('arg1');

        if(Request::initial()->action() == 'research')
            $keyword_str = Request::initial()->param('arg1');

        //latest search
        $this->latest_search = array ();
        $this->pagination = '';
        $this->search_type = 0;

        $tabs = array(
            'domain' => '0',
            'backlinks' => '1',
            'whois' => '2',
            'keywords'   => '3',
            'bulkdomain' => '4'
        );

        $controller = Request::initial()->controller();

        if($this->current_tab)
            $controller = $this->current_tab;

        if(!isset($tabs[$controller]))
        {
            if (array_key_exists('searchtool/domain', $this->tabs))
            {
                $tab = 'domain';
            }
            elseif (!empty($this->tabs))
            {
                foreach ($this->tabs as $key => $value)
                {
                    $tab = explode('/', $key);
                    if (end($tab) == 'keyword')
                    {
                        $sorted_tabs[$tabs['keywords']] = 'keyword';
                    }
                    else
                    {
                        $sorted_tabs[$tabs[end($tab)]] = end($tab);
                    }
                }
                ksort($sorted_tabs);
                $tab = reset($sorted_tabs);
            }
        }
        else
        {
            $tab = $controller;
        }
        if (isset($tab))
        {
            $this->__get_latest_search($tab);

            if (!isset($this->user['role']))
            {
                $this->user['role'] = 'guest';
            }
            if ($tab == 'keywords')
            {
                $tab = 'keyword';
            }

            //HARDCODE because modules is removed & translation for modules
            //removed too
            $this->items['searchtool/domain']['title'] = 'Domain Report';
            $this->items['searchtool/backlinks']['title'] = 'Backlink Validation';
            $this->items['searchtool/whois']['title'] = 'Reverse WHOIS';
            $this->items['searchtool/keyword']['title'] = 'Keyword research';
            $this->items['searchtool/bulkdomain']['title'] = 'Bulk Domains';

            $this->items['searchtool/domain']['description'] = 'WHOIS and Domain Report';
            $this->items['searchtool/backlinks']['description'] = 'Check backlinks';
            $this->items['searchtool/whois']['description'] = 'Search domains on Owner/Address';
            $this->items['searchtool/keyword']['description'] = 'research keywords';
            $this->items['searchtool/bulkdomain']['description'] = 'Bulk Domains';

            $this->tab_items = $this->items;
            $this->role = $this->user['role'];
            $this->search_str = $search_str;
            $this->search_keyword = $keyword_str;
            $this->tab = $tab;

            return parent::render($file);
        }
        return '';
    }
    /* helper function for latest search queries for user
     * @param $page (integer) page number for pagination
     * @return void
     */
    private function __user_latest_search($page,$user_info)
    {
            if($this->search_type == 0)
                return;
            $request = Client::factory('UserSearch');
            $request->filter = array(
                'user_id' => $user_info['id'],
                'search_type' => $this->search_type
            );
            $request->order_by = array("id desc");
            $request->paginate = array('page' => $page, 'per_page' => 5);
            $latest = $request->find_all();

            $max_count = 25;
            if( !$latest )
                return;

            if($latest->count < 25)
                $max_count = $latest->count;
            $pagination = Pagination::factory(array(
                    'total_items'   => $max_count,
                    'auto_hide'     => true,
                    'view'          => 'pagination/floating_directory',
                    'items_per_page'=> 5,
                    'current_page'  => array('source' => 'mixed' ,
                    'key' => 'latest_page')
            ));
            $this->latest_search = $latest->records;
            $this->pagination = $pagination->render();
    }
    /* helper function for latest search queries for guest from Session
     * @param $page (integer) page number for pagination
     * @return void
     * array
     *    'domains' =>
     *      array
     *        0 =>
     *          array
     *            'search_value' => string 'cpanelhosting.com' (length=9)
     *            'search_type' => int 1
     *    'keywords' =>
     *      array
     *        0 =>
     *          array
     *            'search_value' => string 'hosting' (length=7)
     *            'search_type' => int 2
     */

    private function __guest_latest_search($page)
    {
        $session = Session::instance();

        $latest_search = $session->get('latest_search',array());

        if($this->search_type == 0)
                return;
        if($this->search_type == 1)
            $latest_search = Arr::get($latest_search,'domains');
        elseif($this->search_type == 2)
            $latest_search = Arr::get($latest_search,'keywords');

        if(Request::current()->action() == 'site'
            || Request::current()->action() == 'research')
        {
          $latest_search = $this->__guest_latest_search_add($session,$latest_search);
        }
        $session->write();
        $pagination = Pagination::factory(array(
                    'total_items'   => count($latest_search),
                    'auto_hide'     => true,
                    'items_per_page'=> 5,
                    'view'          => 'pagination/floating_directory',
                    'current_page'  => array('source' => 'mixed' ,
                    'key' => 'latest_page')
        ));

        $this->pagination = $pagination->render();

        $start_index = 0;
        $end_index = 5;
        $latest_tmp = array();
        if($page != 0 )
        {
            $start_index = ($page + 5);
            $end_index = $start_index + 5;
        }
        for($i = $start_index; $i <= $end_index; $i++)
        {
            if(isset($latest_search[$i]))
            {
                $latest_tmp[] = $latest_search[$i];
            }
        }
        $this->latest_search = $latest_tmp;
    }

    /*
     *  helper function set Searching string to latest_search variable in session
     *  @param $session - current Session instance
     *  @param $latest_search - array with previosly searching results
     *  @return array
     */
    private function __guest_latest_search_add($session,$latest_search)
    {
        if(!$this->found)
            return $latest_search;
        $search_value = '';
        $search_type  = '';

        $search_value = Request::initial()->param('arg1');
        if(Arr::path($latest_search,'0.search_value') != $search_value)
        {
            if (empty($latest_search))
            {
                $latest_search = array();
            }
            array_unshift($latest_search, array(
                'search_value' => $search_value,
                'search_type'  => $this->search_type
            ));
        }

        while( count($latest_search) > 25 )
        {
            array_pop($latest_search);
        }

        $latest = $session->get('latest_search',array());

        if($this->search_type == 1)
            $latest['domains'] = $latest_search ;
        elseif($this->search_type == 2)
            $latest['keywords'] = $latest_search;

        session::instance()->set('latest_search',$latest);

        return $latest_search;
    }
    /*
     * Helper function to get latest search for users or guest
     * @param $module (string) name of controller
     * @return void
     */
    private function __get_latest_search($module)
    {
        $user_info = $this->user;
        switch($module)
        {
            case 'keywords':
                $this->search_type = 2;
                break;
            case 'backlinks':
                $this->search_type = 0;
                break;
            case 'whois':
                $this->search_type = 0;
                break;
            case 'directory':
                $this->search_type = 1;
                break;
            case 'bulkdomain':
                $this->search_type = 0;
                break;
            default:
                $this->search_type = 1;
                break;
        }
        $page = 0;
        if(Input::get('latest_page',FALSE))
            $page = Input::get('latest_page',FALSE);
        if($page > 0)
            $page = $page - 1;
        if(count($user_info) == 0)
            $this->__guest_latest_search($page);
        else
            $this->__user_latest_search($page,$user_info);
    }
}