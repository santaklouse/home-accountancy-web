<?php defined('SYSPATH') or die('No direct script access.');

$header_links = array(
    '' => UTF8::ucfirst(__('dashboard')),
    'admin' => UTF8::ucfirst(__('admin_dashboard')),
    'panel' => UTF8::ucfirst(__('home_accounting')),
);
$request = Request::current();

$drop_down_menu_links = array(
    array(
        'link' => 'admin/clear_cache',
        'class' => 'icon-refresh',
        'name' => __('clear cache'),
    ),
    array(
        //separator
    ),
    array(
        'link' => 'translations/',
        'class' => 'icon-ru-flag',
        'name' => __('translations'),
    ),
    array(
        'link' => 'tasks/',
        'class' => 'icon-time',
        'name' => __('tasks'),
    ),
    array(
        //separator
    ),
    array(
        'link' => 'users/account_info',
        'class' => 'icon-user',
        'name' => __('account'),
    ),
    array(
        'link' => 'users/settings',
        'class' => 'icon-wrench',
        'name' => __('settings'),
    ),
    array(
        //separator
    ),
    array(
        'link' => 'users/logout',
        'class' => 'icon-off',
        'name' => __('logout'),
    ),
);

?>
<div class="navbar navbar-inverse navbar-top">
    <div class="navbar-inner">
        <div class="container">
            <?php
            foreach ($header_links as $uri => $name)
            {
                $class = 'brand';
                $link = URL::site().$uri;
                if (Helper_Url::current_url($request, $uri))
                {
                    $class .= ' current';
                }
                echo '<a class="'.$class.'" href="'.$link.'">'.$name.'</a>';
            }
            ?>
            <ul class="nav nav-pills pull-right">
                <li class="dropdown" id="menu1">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#menu1">
                        <img src="<?php echo Url::base(TRUE,TRUE);?>/media/images/icons/configure.png" />
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <?php
                            foreach ($drop_down_menu_links as $data)
                            {
                                if (empty($data))
                                {
                                    echo '<li class="divider"></li>';
                                    continue;
                                }
                                $name = UTF8::ucfirst(Arr::get($data, 'name'));
                                echo '<li>';
                                    echo '<a href="'.URL::site(Arr::get($data, 'link')).'">';
                                        echo '<i class="'.Arr::get($data, 'class').'"></i>';
                                        echo '&nbsp;'.$name;
                                    echo '</a>';
                                echo '</li>';
                            }
                        ?>
                    </ul>
                </li>
            </ul>

        </div>
    </div>
</div>