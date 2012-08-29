<?php defined('SYSPATH') or die('No direct script access.');
//generating & POST form
echo '<div id="processing">'.mb_ucfirst(__('processing...')).'</div>';
    echo Form::open($submit_url, array(
        'id' => "gateway_form",
        'name' => "gateway_form"
    ));
    foreach ($inputs as $name => $value)
    {
        echo Form::hidden($name, $value);
    }
    echo Form::close();

?>

<script type="text/javascript">
    document.forms['gateway_form'].submit();
</script>
