<?php /* Smarty version 2.6.19, created on 2012-08-29 14:40:34
         compiled from addfileform_new.tpl */ ?>
<div class="block_in_wrapper">


	<h2><?php echo $this->_config[0]['vars']['addfile']; ?>
</h2>
	<?php echo $this->_config[0]['vars']['maxsize']; ?>
: <?php echo $this->_tpl_vars['postmax']; ?>
<br/><br/>
	<form novalidate class="main" action="#" method="post" enctype="multipart/form-data">
	<fieldset>
		<div class = "row">
			<label for = "upfolder"><?php echo $this->_config[0]['vars']['folder']; ?>
:</label>
			<select name = "upfolder" id = "upfolder">
			<option value = ""><?php echo $this->_config[0]['vars']['rootdir']; ?>
</option>
			<?php unset($this->_sections['fold']);
$this->_sections['fold']['name'] = 'fold';
$this->_sections['fold']['loop'] = is_array($_loop=$this->_tpl_vars['allfolders']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['fold']['show'] = true;
$this->_sections['fold']['max'] = $this->_sections['fold']['loop'];
$this->_sections['fold']['step'] = 1;
$this->_sections['fold']['start'] = $this->_sections['fold']['step'] > 0 ? 0 : $this->_sections['fold']['loop']-1;
if ($this->_sections['fold']['show']) {
    $this->_sections['fold']['total'] = $this->_sections['fold']['loop'];
    if ($this->_sections['fold']['total'] == 0)
        $this->_sections['fold']['show'] = false;
} else
    $this->_sections['fold']['total'] = 0;
if ($this->_sections['fold']['show']):

            for ($this->_sections['fold']['index'] = $this->_sections['fold']['start'], $this->_sections['fold']['iteration'] = 1;
                 $this->_sections['fold']['iteration'] <= $this->_sections['fold']['total'];
                 $this->_sections['fold']['index'] += $this->_sections['fold']['step'], $this->_sections['fold']['iteration']++):
$this->_sections['fold']['rownum'] = $this->_sections['fold']['iteration'];
$this->_sections['fold']['index_prev'] = $this->_sections['fold']['index'] - $this->_sections['fold']['step'];
$this->_sections['fold']['index_next'] = $this->_sections['fold']['index'] + $this->_sections['fold']['step'];
$this->_sections['fold']['first']      = ($this->_sections['fold']['iteration'] == 1);
$this->_sections['fold']['last']       = ($this->_sections['fold']['iteration'] == $this->_sections['fold']['total']);
?>
			<option value = "<?php echo $this->_tpl_vars['allfolders'][$this->_sections['fold']['index']]['ID']; ?>
"><?php echo $this->_tpl_vars['allfolders'][$this->_sections['fold']['index']]['abspath']; ?>
</option>
			<?php endfor; endif; ?>
			</select>
		</div>

		<div id = "inputs">

				<div class="row"><label for="file"><?php echo $this->_config[0]['vars']['file']; ?>
:</label>
						<div class="fileinput" >
						<input size = "1" type="file" class = "file"  name="userfile1" id="filer"  realname="<?php echo $this->_config[0]['vars']['file']; ?>
" onchange = "uploader.fileInfo();" style = "cursor:pointer;" multiple />
						<table class = "faux" cellpadding="0" cellspacing="0" border="0" style="padding:0;margin:0;border:none;">
							<tr>
								<td class="choose" style = "padding:0px;"><button class="inner" onclick="return false;" style = "float:left;cursor:pointer;"><?php echo $this->_config[0]['vars']['chooseone']; ?>
</button></td>
							</tr>
						</table>
					</div>


				</div>

<div class = "row">
<label>&nbsp;</label>
	<div  id = "fileInfo1"></div>
</div>

			</div>

			<div class = "row">
				<label><?php echo $this->_config[0]['vars']['notify']; ?>
:</label>
				<select id = "sendto" name = "sendto[]" multiple style = "height:100px;">
					<option value = "" disabled style = "color:black;font-weight:bold;"><?php echo $this->_config[0]['vars']['general']; ?>
</option>
					<option value = "all" selected><?php echo $this->_config[0]['vars']['all']; ?>
</option>
					<option value = "none" ><?php echo $this->_config[0]['vars']['none']; ?>
</option>
					<option value = "" disabled style = "color:black;font-weight:bold;"><?php echo $this->_config[0]['vars']['members']; ?>
</option>
					<?php unset($this->_sections['member']);
$this->_sections['member']['name'] = 'member';
$this->_sections['member']['loop'] = is_array($_loop=$this->_tpl_vars['members']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['member']['show'] = true;
$this->_sections['member']['max'] = $this->_sections['member']['loop'];
$this->_sections['member']['step'] = 1;
$this->_sections['member']['start'] = $this->_sections['member']['step'] > 0 ? 0 : $this->_sections['member']['loop']-1;
if ($this->_sections['member']['show']) {
    $this->_sections['member']['total'] = $this->_sections['member']['loop'];
    if ($this->_sections['member']['total'] == 0)
        $this->_sections['member']['show'] = false;
} else
    $this->_sections['member']['total'] = 0;
if ($this->_sections['member']['show']):

            for ($this->_sections['member']['index'] = $this->_sections['member']['start'], $this->_sections['member']['iteration'] = 1;
                 $this->_sections['member']['iteration'] <= $this->_sections['member']['total'];
                 $this->_sections['member']['index'] += $this->_sections['member']['step'], $this->_sections['member']['iteration']++):
$this->_sections['member']['rownum'] = $this->_sections['member']['iteration'];
$this->_sections['member']['index_prev'] = $this->_sections['member']['index'] - $this->_sections['member']['step'];
$this->_sections['member']['index_next'] = $this->_sections['member']['index'] + $this->_sections['member']['step'];
$this->_sections['member']['first']      = ($this->_sections['member']['iteration'] == 1);
$this->_sections['member']['last']       = ($this->_sections['member']['iteration'] == $this->_sections['member']['total']);
?>
						<option value = "<?php echo $this->_tpl_vars['members'][$this->_sections['member']['index']]['ID']; ?>
" ><?php echo $this->_tpl_vars['members'][$this->_sections['member']['index']]['name']; ?>
</option>
					<?php endfor; endif; ?>
				</select>
			</div>

	<div class = "row" id = "statusrow" style = "display:none;">
		<label>&nbsp;</label>
		<br />
		<div class="statusbar" id = "fakeprogress" style = "width:314px;margin-left:140px;">
			<div id="completed" class="complete" style="width: 0%;"></div>
		</div>
	</div>

	<div class="row-butn-bottom">
		<label>&nbsp;</label>
		<div id = "filesubmit" >
			<button onclick = "$('statusrow').show();uploader.upload();return false;" onfocus="this.blur();"><?php echo $this->_config[0]['vars']['addbutton']; ?>
</button>
		</div>
	</div>


	</fieldset>
	</form>
<?php echo '
<script type="text/javascript">
		Event.observe(window,"load",function()
		{

		uploader = new html5up("filer","fileInfo1","completed","managefile.php?action=uploadAsync&id='; ?>
<?php echo $this->_tpl_vars['project']['ID']; ?>
<?php echo '");
		});
	</script>
'; ?>

</div> 