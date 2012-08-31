<?php defined('SYSPATH') or die('No direct script access.');

$header_links = array(
    '' => __('dashboard'),
);

$rightside_header_links = array(
    array(
        'link' => '',
        'class' => '',
        'name' => UTF8::ucfirst('home'),
    ),
    array(
        'link' => 'welcome/about_us',
        'class' => '',
        'name' => UTF8::ucfirst('about_us'),
    ),
    array(
        'link' => 'users/register',
        'class' => '',
        'name' => UTF8::ucfirst('register'),
    ),
    array(
        'link' => 'users/login',
        'class' => '',
        'name' => UTF8::ucfirst('login'),
    ),
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
                    <div class="languages-container left">
                        <?php echo Helper_Language::selector();?>
                    </div>
                    <?php
                        $request = Request::current();
                        foreach ($rightside_header_links as $data)
                        {
                            $name = UTF8::ucfirst(Arr::get($data, 'name'));
                            $class = Arr::get($data, 'class', '');
                            $uri = Arr::get($data, 'link');
                            if (Helper_Url::current_url($request, $uri))
                            {
                                $class .= ' active';
                            }
                            echo '<li class="'.$class.'">';
                                echo '<a href="'.URL::site($uri).'">';
                                    echo '<i></i>';
                                    echo '&nbsp;'.$name;
                                echo '</a>';
                            echo '</li>';
                        }
                    ?>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>