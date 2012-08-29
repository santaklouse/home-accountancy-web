<?php defined('SYSPATH') or die('No direct script access.');

$site_name = Kohana::$config->load('site.title');

echo '<div style="margin: 0 auto;width: 300px;">';
    echo mb_ucfirst(__('thank_you_for_your_purchase'));
    echo URL::link_to(
        array('root'),
        'Click for return to '.$site_name
    );
echo '</div>';

?>

<script type="text/javascript">
    site_url='<?php echo Url::path(array('root'));?>'
    window.setTimeout(function(){
        window.location = site_url;
    }, 10000);
</script>
