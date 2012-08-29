<?php /* Smarty version 2.6.19, created on 2012-08-29 14:01:45
         compiled from adduserform.tpl */ ?>
<div class="block_in_wrapper">
	<form novalidate class="main" method="post" action="admin.php?action=adduser" <?php echo 'onsubmit="return validateCompleteForm(this);"'; ?>
>
		<fieldset>

			<div class="row">
				<label for="name"><?php echo $this->_config[0]['vars']['name']; ?>
:</label>
				<input type="text" name="name" id="name" required="1" realname="<?php echo $this->_config[0]['vars']['name']; ?>
" />
			</div>
			<div class="row">
				<label for="company"><?php echo $this->_config[0]['vars']['company']; ?>
:</label>
				<input type="text" name="company" id="company" realname="<?php echo $this->_config[0]['vars']['company']; ?>
" />
			</div>
			<div class="row">
				<label for="email"><?php echo $this->_config[0]['vars']['email']; ?>
:</label>
				<input type="text" name="email" id="email" realname="<?php echo $this->_config[0]['vars']['email']; ?>
" />
			</div>
			<div class="row">
				<label for="pass"><?php echo $this->_config[0]['vars']['password']; ?>
:</label>
				<input type="text" name="pass" id="pass" required="1" realname="<?php echo $this->_config[0]['vars']['password']; ?>
" />
			</div>
			<div class = "row">
				<label id = "rate"><?php echo $this->_config[0]['vars']['rate']; ?>
:</label>
				<input type = "text" name = "rate" id = "rate" />
			</div>

			<?php if ($this->_tpl_vars['projects']): ?> 				<div class="clear_both_b"></div>

				<div class="row">
					<label><?php echo $this->_config[0]['vars']['projects']; ?>
:</label>
					<div style="float:left;">
						<?php unset($this->_sections['project']);
$this->_sections['project']['name'] = 'project';
$this->_sections['project']['loop'] = is_array($_loop=$this->_tpl_vars['projects']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['project']['show'] = true;
$this->_sections['project']['max'] = $this->_sections['project']['loop'];
$this->_sections['project']['step'] = 1;
$this->_sections['project']['start'] = $this->_sections['project']['step'] > 0 ? 0 : $this->_sections['project']['loop']-1;
if ($this->_sections['project']['show']) {
    $this->_sections['project']['total'] = $this->_sections['project']['loop'];
    if ($this->_sections['project']['total'] == 0)
        $this->_sections['project']['show'] = false;
} else
    $this->_sections['project']['total'] = 0;
if ($this->_sections['project']['show']):

            for ($this->_sections['project']['index'] = $this->_sections['project']['start'], $this->_sections['project']['iteration'] = 1;
                 $this->_sections['project']['iteration'] <= $this->_sections['project']['total'];
                 $this->_sections['project']['index'] += $this->_sections['project']['step'], $this->_sections['project']['iteration']++):
$this->_sections['project']['rownum'] = $this->_sections['project']['iteration'];
$this->_sections['project']['index_prev'] = $this->_sections['project']['index'] - $this->_sections['project']['step'];
$this->_sections['project']['index_next'] = $this->_sections['project']['index'] + $this->_sections['project']['step'];
$this->_sections['project']['first']      = ($this->_sections['project']['iteration'] == 1);
$this->_sections['project']['last']       = ($this->_sections['project']['iteration'] == $this->_sections['project']['total']);
?>
							<div class="row">
								<input type="checkbox" class="checkbox" value="<?php echo $this->_tpl_vars['projects'][$this->_sections['project']['index']]['ID']; ?>
" name="assignto[]" id="<?php echo $this->_tpl_vars['projects'][$this->_sections['project']['index']]['ID']; ?>
" /><label for="<?php echo $this->_tpl_vars['projects'][$this->_sections['project']['index']]['ID']; ?>
" style="width:210px;"><?php echo $this->_tpl_vars['projects'][$this->_sections['project']['index']]['name']; ?>
</label>
							</div>
						<?php endfor; endif; ?>
					</div>
				</div>

				<div class="clear_both_b"></div>
			<?php endif; ?>

			<div class="row">
				<label><?php echo $this->_config[0]['vars']['role']; ?>
:</label>
				<select name = "role" realname = "<?php echo $this->_config[0]['vars']['role']; ?>
" required="1" exclude = "-1" id = "roleselect">
					<option value="-1" selected="selected"><?php echo $this->_config[0]['vars']['chooseone']; ?>
</option>
					<?php unset($this->_sections['role']);
$this->_sections['role']['name'] = 'role';
$this->_sections['role']['loop'] = is_array($_loop=$this->_tpl_vars['roles']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['role']['show'] = true;
$this->_sections['role']['max'] = $this->_sections['role']['loop'];
$this->_sections['role']['step'] = 1;
$this->_sections['role']['start'] = $this->_sections['role']['step'] > 0 ? 0 : $this->_sections['role']['loop']-1;
if ($this->_sections['role']['show']) {
    $this->_sections['role']['total'] = $this->_sections['role']['loop'];
    if ($this->_sections['role']['total'] == 0)
        $this->_sections['role']['show'] = false;
} else
    $this->_sections['role']['total'] = 0;
if ($this->_sections['role']['show']):

            for ($this->_sections['role']['index'] = $this->_sections['role']['start'], $this->_sections['role']['iteration'] = 1;
                 $this->_sections['role']['iteration'] <= $this->_sections['role']['total'];
                 $this->_sections['role']['index'] += $this->_sections['role']['step'], $this->_sections['role']['iteration']++):
$this->_sections['role']['rownum'] = $this->_sections['role']['iteration'];
$this->_sections['role']['index_prev'] = $this->_sections['role']['index'] - $this->_sections['role']['step'];
$this->_sections['role']['index_next'] = $this->_sections['role']['index'] + $this->_sections['role']['step'];
$this->_sections['role']['first']      = ($this->_sections['role']['iteration'] == 1);
$this->_sections['role']['last']       = ($this->_sections['role']['iteration'] == $this->_sections['role']['total']);
?>
						<option value = "<?php echo $this->_tpl_vars['roles'][$this->_sections['role']['index']]['ID']; ?>
" id="role<?php echo $this->_tpl_vars['roles'][$this->_sections['role']['index']]['ID']; ?>
"><?php echo $this->_tpl_vars['roles'][$this->_sections['role']['index']]['name']; ?>
</option>
					<?php endfor; endif; ?>
				</select>
			</div>

			<div class="clear_both_b"></div>

			<div class="row">
				<label>&nbsp;</label>
				<div class="butn">
					<button type="submit"><?php echo $this->_config[0]['vars']['addbutton']; ?>
</button>
				</div>
				<a href = "javascript:blindtoggle('form_member');" class="butn_link"><span><?php echo $this->_config[0]['vars']['cancel']; ?>
</span></a>
			</div>

		</fieldset>
	</form>
</div> 