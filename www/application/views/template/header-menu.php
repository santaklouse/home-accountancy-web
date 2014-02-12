<header class="navbar navbar-inverse navbar-static-top bs-docs-nav" role="banner">
    <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <?=HTML::anchor('/', 'Home', array('class' => 'navbar-brand'))?>
        </div>
        <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
            <ul class="nav navbar-nav">
<!--                <li class="active">-->
<!--                    <a href="../getting-started">Getting started</a>-->
<!--                </li>-->
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if (Auth::instance()->logged_in()) :?>
                    <li><?=HTML::anchor('users/logout', 'Logout')?></li>
                <?php endif;?>
            </ul>
        </nav>
    </div>
</header>