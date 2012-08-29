<?php /* Smarty version 2.6.19, created on 2012-08-29 14:40:33
         compiled from adduserproject.tpl */ ?>
<div class="block_in_wrapper">

	<form novalidate class="main" method="post" action="manageproject.php?action=assign&amp;id=<?php echo $this->_tpl_vars['project']['ID']; ?>
" <?php echo 'onsubmit="return validateCompleteForm(this);"'; ?>
>
	<fieldset>

		<div class="row">
			<label for="addtheuser"><?php echo $this->_config[0]['vars']['user']; ?>
</label>
			<select name = "user" id="addtheuser" required = "1" exclude = "-1" realname = "<?php echo $this->_config[0]['vars']['user']; ?>
">
				<option value="-1" selected="selected"><?php echo $this->_config[0]['vars']['chooseone']; ?>
</option>
					<?php unset($this->_sections['usr']);
$this->_sections['usr']['name'] = 'usr';
$this->_sections['usr']['loop'] = is_array($_loop=$this->_tpl_vars['users']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['usr']['show'] = true;
$this->_sections['usr']['max'] = $this->_sections['usr']['loop'];
$this->_sections['usr']['step'] = 1;
$this->_sections['usr']['start'] = $this->_sections['usr']['step'] > 0 ? 0 : $this->_sections['usr']['loop']-1;
if ($this->_sections['usr']['show']) {
    $this->_sections['usr']['total'] = $this->_sections['usr']['loop'];
    if ($this->_sections['usr']['total'] == 0)
        $this->_sections['usr']['show'] = false;
} else
    $this->_sections['usr']['total'] = 0;
if ($this->_sections['usr']['show']):

            for ($this->_sections['usr']['index'] = $this->_sections['usr']['start'], $this->_sections['usr']['iteration'] = 1;
                 $this->_sections['usr']['iteration'] <= $this->_sections['usr']['total'];
                 $this->_sections['usr']['index'] += $this->_sections['usr']['step'], $this->_sections['usr']['iteration']++):
$this->_sections['usr']['rownum'] = $this->_sections['usr']['iteration'];
$this->_sections['usr']['index_prev'] = $this->_sections['usr']['index'] - $this->_sections['usr']['step'];
$this->_sections['usr']['index_next'] = $this->_sections['usr']['index'] + $this->_sections['usr']['step'];
$this->_sections['usr']['first']      = ($this->_sections['usr']['iteration'] == 1);
$this->_sections['usr']['last']       = ($this->_sections['usr']['iteration'] == $this->_sections['usr']['total']);
?>
						<option value = "<?php echo $this->_tpl_vars['users'][$this->_sections['usr']['index']]['ID']; ?>
"><?php echo $this->_tpl_vars['users'][$this->_sections['usr']['index']]['name']; ?>
</option>
					<?php endfor; endif; ?>
			</select>
		</div>

	<div class="row-butn-bottom">
		<label>&nbsp;</label>
		<button type="submit" onfocus="this.blur();"><?php echo $this->_config[0]['vars']['addbutton']; ?>
</button>
		<button onclick="blindtoggle('form_member');toggleClass('addmember','add-active','add');toggleClass('add_butn_member','butn_link_active','butn_link');toggleClass('sm_member','smooth','nosmooth');return false;" onfocus="this.blur();"><?php echo $this->_config[0]['vars']['cancel']; ?>
</button>
	</div>


	</fieldset>
	</form>

</div> 