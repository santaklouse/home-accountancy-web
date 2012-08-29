<?php defined('SYSPATH') or die('No direct script access.');

    echo View::factory('layout/header', get_defined_vars())->render();

    if ( ! Auth::instance()->logged_in())
        echo View::factory('layout/menu/public', get_defined_vars())->render();
    elseif (Auth::instance()->has_role('admin'))
        echo View::factory('layout/menu/admin', get_defined_vars())->render();
    else
        echo View::factory('layout/menu/user', get_defined_vars())->render();
?>

    <div class="container">
        <?php echo $content; ?>
    </div> <!-- /container -->

<?php
    echo View::factory('layout/footer', get_defined_vars())->render();
?>
