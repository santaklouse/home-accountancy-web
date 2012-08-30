<?php /* Smarty version 2.6.19, created on 2012-08-30 13:16:24
         compiled from addmymessage.tpl */ ?>
<div class="block_in_wrapper">

	<h2><?php echo $this->_config[0]['vars']['addmessage']; ?>
</h2>

	<form novalidate class="main" id="add_msgs" method="post" enctype="multipart/form-data" action="managemessage.php?action=add&amp;id=<?php echo $this->_tpl_vars['myprojects'][$this->_sections['project']['index']]['ID']; ?>
" <?php echo ' onsubmit="return validateCompleteForm(this,\'input_error\');"'; ?>
 >
	<fieldset>


		<div class="row"><label for="title"><?php echo $this->_config[0]['vars']['title']; ?>
:</label><input type="text" name="title" id="title" required="1" realname="<?php echo $this->_config[0]['vars']['title']; ?>
" /></div>
		<div class="row"><label for="text"><?php echo $this->_config[0]['vars']['text']; ?>
:</label><div class="editor"><textarea name="text" id="text<?php echo $this->_tpl_vars['myprojects'][$this->_sections['project']['index']]['ID']; ?>
"  realname="<?php echo $this->_config[0]['vars']['text']; ?>
" rows="3" cols="1" ></textarea></div></div>


		<div class="row">
			<label><?php echo $this->_config[0]['vars']['files']; ?>
</label>
			<button class="inner" onclick="blindtoggle('files-add<?php echo $this->_tpl_vars['myprojects'][$this->_sections['project']['index']]['ID']; ?>
');toggleClass(this,'inner-active','inner');return false;" onfocus="this.blur()"><?php echo $this->_config[0]['vars']['addbutton']; ?>
</button>
			<button class="inner" onclick="blindtoggle('files-attach<?php echo $this->_tpl_vars['myprojects'][$this->_sections['project']['index']]['ID']; ?>
');toggleClass(this,'inner-active','inner');return false;" onfocus="this.blur()"><?php echo $this->_config[0]['vars']['attachbutton']; ?>
</button>
		</div>


				<div id = "files-attach<?php echo $this->_tpl_vars['myprojects'][$this->_sections['project']['index']]['ID']; ?>
" class="blinded" style = "display:none;clear:both;">

		   	<div class="row">
				<label for = "thefiles"><?php echo $this->_config[0]['vars']['attachfile']; ?>
</label>
					<select name = "mode" id = "thefiles">
						<option value = "0"><?php echo $this->_config[0]['vars']['chooseone']; ?>
</option>
						<?php unset($this->_sections['file']);
$this->_sections['file']['name'] = 'file';
$this->_sections['file']['loop'] = is_array($_loop=$this->_tpl_vars['myprojects'][$this->_sections['project']['index']]['files']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['file']['show'] = true;
$this->_sections['file']['max'] = $this->_sections['file']['loop'];
$this->_sections['file']['step'] = 1;
$this->_sections['file']['start'] = $this->_sections['file']['step'] > 0 ? 0 : $this->_sections['file']['loop']-1;
if ($this->_sections['file']['show']) {
    $this->_sections['file']['total'] = $this->_sections['file']['loop'];
    if ($this->_sections['file']['total'] == 0)
        $this->_sections['file']['show'] = false;
} else
    $this->_sections['file']['total'] = 0;
if ($this->_sections['file']['show']):

            for ($this->_sections['file']['index'] = $this->_sections['file']['start'], $this->_sections['file']['iteration'] = 1;
                 $this->_sections['file']['iteration'] <= $this->_sections['file']['total'];
                 $this->_sections['file']['index'] += $this->_sections['file']['step'], $this->_sections['file']['iteration']++):
$this->_sections['file']['rownum'] = $this->_sections['file']['iteration'];
$this->_sections['file']['index_prev'] = $this->_sections['file']['index'] - $this->_sections['file']['step'];
$this->_sections['file']['index_next'] = $this->_sections['file']['index'] + $this->_sections['file']['step'];
$this->_sections['file']['first']      = ($this->_sections['file']['iteration'] == 1);
$this->_sections['file']['last']       = ($this->_sections['file']['iteration'] == $this->_sections['file']['total']);
?>
						<option value = "<?php echo $this->_tpl_vars['myprojects'][$this->_sections['project']['index']]['files'][$this->_sections['file']['index']]['ID']; ?>
"><?php echo $this->_tpl_vars['myprojects'][$this->_sections['project']['index']]['files'][$this->_sections['file']['index']]['name']; ?>
</option>
						<?php endfor; endif; ?>
					</select>
			</div>

		</div>


				<div id = "files-add<?php echo $this->_tpl_vars['myprojects'][$this->_sections['project']['index']]['ID']; ?>
" class="blinded" style = "display:none;">


			<div class="row">
				<label for = "numfiles<?php echo $this->_tpl_vars['myprojects'][$this->_sections['project']['index']]['ID']; ?>
"><?php echo $this->_config[0]['vars']['count']; ?>
:</label>
				<select name = "numfiles" id = "numfiles<?php echo $this->_tpl_vars['myprojects'][$this->_sections['project']['index']]['ID']; ?>
" onchange = "make_inputs(this.value);">
				<option value = "1" selected="selected">1</option>
				<option value = "2">2</option>
				<option value = "3">3</option>
				<option value = "4">4</option>
				<option value = "5">5</option>
				<option value = "6">6</option>
				<option value = "7">7</option>
				<option value = "8">8</option>
				<option value = "9">9</option>
				<option value = "10">10</option>
				</select>
			</div>

			<div id = "inputs">
				<div class="row"><label for = "title"><?php echo $this->_config[0]['vars']['title']; ?>
:</label><input type = "text" name = "userfile1-title" id="title" /></div>
				<div class="row">	<label for="file"><?php echo $this->_config[0]['vars']['file']; ?>
:</label>
					<div class="fileinput" >
							<input type="file" class="file" name="userfile1" id="file"  realname="<?php echo $this->_config[0]['vars']['file']; ?>
" size="19" onchange = "file_<?php echo $this->_tpl_vars['myprojects'][$this->_sections['project']['index']]['ID']; ?>
.value = this.value;" />
							<table class = "faux" cellpadding="0" cellspacing="0" border="0" style="padding:0;margin:0;border:none;">
								<tr>
								<td><input type="text" class="text-file" name = "file-$myprojects[project].ID" id="file_<?php echo $this->_tpl_vars['myprojects'][$this->_sections['project']['index']]['ID']; ?>
"></td>
								<td class="choose"><button class="inner" onclick="return false;"><?php echo $this->_config[0]['vars']['chooseone']; ?>
</button></td>
								</tr>
							</table>
					</div>
				</div>
				<input type = "hidden" name="desc" id="desc" value = "" />
			</div>

		</div>

		<div class="row"><label for="tags"><?php echo $this->_config[0]['vars']['tags']; ?>
:</label><input type="text" name="tags" id="tags"  realname="<?php echo $this->_config[0]['vars']['tags']; ?>
" /></div>

		<div class="row"><label for="milestone"><?php echo $this->_config[0]['vars']['milestone']; ?>
:</label><select name="milestone" id="milestone"  realname="<?php echo $this->_config[0]['vars']['milestone']; ?>
">
			<option value="0" selected="selected"><?php echo $this->_config[0]['vars']['chooseone']; ?>
</option>
				<?php unset($this->_sections['stone']);
$this->_sections['stone']['name'] = 'stone';
$this->_sections['stone']['loop'] = is_array($_loop=$this->_tpl_vars['myprojects'][$this->_sections['project']['index']]['milestones']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['stone']['show'] = true;
$this->_sections['stone']['max'] = $this->_sections['stone']['loop'];
$this->_sections['stone']['step'] = 1;
$this->_sections['stone']['start'] = $this->_sections['stone']['step'] > 0 ? 0 : $this->_sections['stone']['loop']-1;
if ($this->_sections['stone']['show']) {
    $this->_sections['stone']['total'] = $this->_sections['stone']['loop'];
    if ($this->_sections['stone']['total'] == 0)
        $this->_sections['stone']['show'] = false;
} else
    $this->_sections['stone']['total'] = 0;
if ($this->_sections['stone']['show']):

            for ($this->_sections['stone']['index'] = $this->_sections['stone']['start'], $this->_sections['stone']['iteration'] = 1;
                 $this->_sections['stone']['iteration'] <= $this->_sections['stone']['total'];
                 $this->_sections['stone']['index'] += $this->_sections['stone']['step'], $this->_sections['stone']['iteration']++):
$this->_sections['stone']['rownum'] = $this->_sections['stone']['iteration'];
$this->_sections['stone']['index_prev'] = $this->_sections['stone']['index'] - $this->_sections['stone']['step'];
$this->_sections['stone']['index_next'] = $this->_sections['stone']['index'] + $this->_sections['stone']['step'];
$this->_sections['stone']['first']      = ($this->_sections['stone']['iteration'] == 1);
$this->_sections['stone']['last']       = ($this->_sections['stone']['iteration'] == $this->_sections['stone']['total']);
?>
				<option value="<?php echo $this->_tpl_vars['myprojects'][$this->_sections['project']['index']]['milestones'][$this->_sections['stone']['index']]['ID']; ?>
"><?php echo $this->_tpl_vars['myprojects'][$this->_sections['project']['index']]['milestones'][$this->_sections['stone']['index']]['name']; ?>
</option>
				<?php endfor; endif; ?>
			</select>
		</div>

		<div class="row-butn-bottom">
			<label>&nbsp;</label>
			<button type="submit" onfocus="this.blur()"><?php echo $this->_config[0]['vars']['send']; ?>
</button>
			<button onclick="blindtoggle('addmsg<?php echo $this->_tpl_vars['myprojects'][$this->_sections['project']['index']]['ID']; ?>
');toggleClass('add_<?php echo $this->_tpl_vars['myprojects'][$this->_sections['project']['index']]['ID']; ?>
','add-active','add');toggleClass('add_butn_<?php echo $this->_tpl_vars['myprojects'][$this->_sections['project']['index']]['ID']; ?>
','butn_link_active','butn_link');toggleClass('sm_<?php echo $this->_tpl_vars['myprojects'][$this->_sections['project']['index']]['ID']; ?>
','smooth','nosmooth');return false;" onfocus="this.blur()"><?php echo $this->_config[0]['vars']['cancel']; ?>
</button>
		</div>

	</fieldset>
	</form>

</div> 