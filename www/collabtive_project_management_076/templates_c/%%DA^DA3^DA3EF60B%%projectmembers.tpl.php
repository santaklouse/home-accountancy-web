<?php /* Smarty version 2.6.19, created on 2012-08-29 14:40:33
         compiled from projectmembers.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'truncate', 'projectmembers.tpl', 27, false),array('function', 'paginate_prev', 'projectmembers.tpl', 143, false),array('function', 'paginate_middle', 'projectmembers.tpl', 143, false),array('function', 'paginate_next', 'projectmembers.tpl', 143, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array('jsload' => 'ajax')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tabsmenue-project.tpl", 'smarty_include_vars' => array('userstab' => 'active')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div id="content-left">
<div id="content-left-in">
<div class="user">

	<div class="infowin_left" style = "display:none;" id = "systemmsg">
		<?php if ($this->_tpl_vars['mode'] == 'added'): ?>
		<span class="info_in_green"><img src="templates/standard/images/symbols/user-icon-male.png" alt=""/><?php echo $this->_config[0]['vars']['userwasadded']; ?>
</span>
		<?php elseif ($this->_tpl_vars['mode'] == 'edited'): ?>
		<span class="info_in_yellow"><img src="templates/standard/images/symbols/user-icon-male.png" alt=""/><?php echo $this->_config[0]['vars']['userwasedited']; ?>
</span>
		<?php elseif ($this->_tpl_vars['mode'] == 'deleted'): ?>
		<span class="info_in_red"><img src="templates/standard/images/symbols/user-icon-male.png" alt=""/><?php echo $this->_config[0]['vars']['userwasdeleted']; ?>
</span>
		<?php elseif ($this->_tpl_vars['mode'] == 'assigned'): ?>
		<span class="info_in_yellow"><img src="templates/standard/images/symbols/user-icon-male.png" alt=""/><?php echo $this->_config[0]['vars']['userwasassigned']; ?>
</span>
		<?php elseif ($this->_tpl_vars['mode'] == 'deassigned'): ?>
		<span class="info_in_yellow"><img src="templates/standard/images/symbols/user-icon-male.png" alt=""/><?php echo $this->_config[0]['vars']['userwasdeassigned']; ?>
</span>
		<?php endif; ?>
	</div>
	<?php echo '
	<script type = "text/javascript">
	systemMsg(\'systemmsg\');
	 </script>
	'; ?>


<h1><?php echo ((is_array($_tmp=$this->_tpl_vars['projectname'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 45, "...", true) : smarty_modifier_truncate($_tmp, 45, "...", true)); ?>
<span>/ <?php echo $this->_config[0]['vars']['members']; ?>
</span></h1>



			<div class="headline">
				<a href="javascript:void(0);" id="block_members_toggle" class="win_block" onclick = "toggleBlock('block_members');"></a>

				<div class="wintools">
					<?php if ($this->_tpl_vars['userpermissions']['admin']['add']): ?>
					<a class="add" href="javascript:blindtoggle('form_member');" id="addmember" onclick="toggleClass(this,'add-active','add');toggleClass('add_butn_member','butn_link_active','butn_link');toggleClass('sm_member','smooth','nosmooth');"><span><?php echo $this->_config[0]['vars']['adduser']; ?>
</span></a>
					<?php endif; ?>
				</div>

				<h2>
					<img src="./templates/standard/images/symbols/userlist.png" alt="" /><?php echo $this->_config[0]['vars']['members']; ?>

				</h2>

			</div>


			<div id="block_members" class="blockwrapper">
								<?php if ($this->_tpl_vars['userpermissions']['admin']['add']): ?>
					<div id = "form_member" class="addmenue" style = "display:none;">
						<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "adduserproject.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
					</div>
				<?php endif; ?>

				<div class="nosmooth" id="sm_member">
					<div class="contenttitle">
						<div class="contenttitle_menue">
													</div>
						<div class="contenttitle_in">
													</div>
					</div>
					<div class="content_in_wrapper">
					<div class="content_in_wrapper_in">


						<div class="inwrapper">
							<ul>
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
								<li>
									<div class="itemwrapper" id="iw_<?php echo $this->_tpl_vars['folders'][$this->_sections['fold']['index']]['ID']; ?>
">

											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td class="leftmen" valign="top">
														<div class="inmenue">
															<?php if ($this->_tpl_vars['members'][$this->_sections['member']['index']]['avatar'] != ""): ?>
																<a class="more" href="javascript:fadeToggle('info_<?php echo $this->_tpl_vars['members'][$this->_sections['member']['index']]['ID']; ?>
');"></a>
															<?php endif; ?>
														</div>
													</td>
													<td class="thumb">
														<a href="manageuser.php?action=profile&amp;id=<?php echo $this->_tpl_vars['members'][$this->_sections['member']['index']]['ID']; ?>
" title="<?php echo $this->_tpl_vars['members'][$this->_sections['member']['index']]['name']; ?>
">
															<?php if ($this->_tpl_vars['members'][$this->_sections['member']['index']]['gender'] == 'f'): ?>
																<img src = "./templates/standard/images/symbols/user-icon-female.png" alt="" />
															<?php else: ?>
																<img src = "./templates/standard/images/symbols/user-icon-male.png" alt="" />
															<?php endif; ?>
														</a>
													</td>
													<td class="rightmen" valign="top">
														<div class="inmenue">
															<?php if ($this->_tpl_vars['userpermissions']['admin']['add']): ?>
															<a class="del" href="manageproject.php?action=deassignform&amp;id=<?php echo $this->_tpl_vars['project']['ID']; ?>
&amp;user=<?php echo $this->_tpl_vars['members'][$this->_sections['member']['index']]['ID']; ?>
" title="<?php echo $this->_config[0]['vars']['deassign']; ?>
"></a>
															<a class="edit" href="admin.php?action=editform&id=<?php echo $this->_tpl_vars['members'][$this->_sections['member']['index']]['ID']; ?>
" title="<?php echo $this->_config[0]['vars']['editfile']; ?>
"></a>
															<?php endif; ?>
														</div>
													</td>
												</tr>
												<tr>
													<td colspan="3">
														<span class="name">
															<a href = "manageuser.php?action=profile&amp;id=<?php echo $this->_tpl_vars['members'][$this->_sections['member']['index']]['ID']; ?>
" title="<?php echo $this->_tpl_vars['members'][$this->_sections['member']['index']]['name']; ?>
">
																<?php if ($this->_tpl_vars['members'][$this->_sections['member']['index']]['name'] != ""): ?>
																	<?php echo ((is_array($_tmp=$this->_tpl_vars['members'][$this->_sections['member']['index']]['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 13, "...", true) : smarty_modifier_truncate($_tmp, 13, "...", true)); ?>

																<?php else: ?>
																	<?php echo $this->_config[0]['vars']['user']; ?>

																<?php endif; ?>
															</a>
														</span>
													</td>
												<tr/>
											</table>

											<?php if ($this->_tpl_vars['members'][$this->_sections['member']['index']]['avatar'] != ""): ?>
											<div class="moreinfo-wrapper">
												<div class="moreinfo" id="info_<?php echo $this->_tpl_vars['members'][$this->_sections['member']['index']]['ID']; ?>
" style="display:none">
													<img src = "thumb.php?pic=files/<?php echo $this->_tpl_vars['cl_config']; ?>
/avatar/<?php echo $this->_tpl_vars['members'][$this->_sections['member']['index']]['avatar']; ?>
&amp;width=82" alt="" onclick="fadeToggle('info_<?php echo $this->_tpl_vars['members'][$this->_sections['member']['index']]['ID']; ?>
');" />
													<span class="name"><a href="manageuser.php?action=profile&amp;id=<?php echo $this->_tpl_vars['members'][$this->_sections['member']['index']]['ID']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['members'][$this->_sections['member']['index']]['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 15, "...", true) : smarty_modifier_truncate($_tmp, 15, "...", true)); ?>
</a></span>
												</div>
											</div>
											<?php endif; ?>

									</div> 								</li>
							<?php endfor; endif; ?> 
							</ul>
						</div> 


			</div> 
			</div> 
			<div class="staterow">
				<div class="staterowin">
									</div>

				<div class="staterowin_right"> <span ><?php echo $this->_tpl_vars['langfile']['page']; ?>
 <?php echo smarty_function_paginate_prev(array(), $this);?>
 <?php echo smarty_function_paginate_middle(array(), $this);?>
 <?php echo smarty_function_paginate_next(array(), $this);?>
</span></div>
			</div>


			</div> 			<div class="tablemenue">
					<div class="tablemenue-in">
						<?php if ($this->_tpl_vars['userpermissions']['admin']['add']): ?>
						<a class="butn_link" href="javascript:blindtoggle('form_member');" id="add_butn_member" onclick="toggleClass(this,'butn_link_active','butn_link');toggleClass('addmember','add-active','add');toggleClass('sm_member','smooth','nosmooth');"><?php echo $this->_config[0]['vars']['adduser']; ?>
</a>
						<?php endif; ?>
					</div>
			</div>
			</div> 

<div class="content-spacer"></div>


</div> </div> </div> 
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "sidebar-a.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>