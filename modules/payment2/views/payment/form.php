<form method="POST" id="gateway_form" name="gateway_form" action="<?php echo $submit_url; ?>">
<?php
foreach ($inputs as $name => $value)
{
    echo '<input type="hidden" name="', $name, '" value="', $value, '" />', PHP_EOL;
}
?>
</form>
<script>
document.forms['gateway_form'].submit();
</script>