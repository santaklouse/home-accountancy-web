<?php defined('SYSPATH') or die('No direct script access.');

$header_links = array(
    '' => __('dashboard'),
);
$request = Request::current();
?>
<div class="navbar navbar-inverse navbar-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
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
            <div class="nav-collapse">
                <ul class="nav pull-right">
                    <li class="active"><a href="<?echo URL::site();?>"><?php echo __('home');?></a></li>
                    <li><?php
                        echo Html::anchor(
                            URL::site('welcome/about_us'),
                            __('about_us')
                        );
                        ?></li>
                    <li><?php
                        echo Html::anchor(
                            URL::site('users/register'),
                            __('register')
                        );
                        ?></li>
                    <li><?php
                        echo Html::anchor(
                            URL::site('users/login'),
                            __('login')
                        );
                        ?></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>