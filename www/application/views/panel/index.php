<?php defined('SYSPATH') or die('No direct script access.');?>

<div id="navigation">
    <div class="input-append left">
        <input class="span2" id="appendedInputButton" size="16" type="text" placeholder="<?php echo UTF8::ucfirst(__('enter_the_date'));?>">
        <button id="go-to-date" class="btn" type="button"><?php echo UTF8::ucfirst(__('go'));?>!</button>
    </div>
    <button id="go-today" type="button" class="btn btn-success right">
        <i class="icon-home"></i> <?php echo UTF8::ucfirst(__('go_to_today'));?>
    </button>
    <div class="clear"></div>
</div>
<div class="calendar" id="calendar-tab">
        <div id="calendar-container">
        </div>
</div>
