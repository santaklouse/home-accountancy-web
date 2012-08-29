<?php defined('SYSPATH') or die('No direct script access.');

$header_links = array(
    '' => __('Dashboard'),
    'panel' => 'Домашняя бухгалтерия',
);
$request = Request::current();
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
                        <li><a href="<?php echo Url::site('users/account_info');?>" class="account-info"><i class="icon-user"></i>account</a></li>
                        <li><a href="<?php echo Url::site('users/settings');?>" class="account-settings"><i class="icon-cog"></i>settings</a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo URL::site('users/logout') ;?>"><i class="icon-off"></i>logout</a></li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>
</div>
