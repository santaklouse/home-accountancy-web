<?php /* Smarty version 2.6.19, created on 2012-08-29 14:41:01
         compiled from search.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'truncate', 'search.tpl', 74, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array('jsload' => 'ajax','jsload3' => 'lightbox')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tabsmenue-desk.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div id="content-left">
<div id="content-left-in">
<div class="neutral">

<h1><?php echo $this->_config[0]['vars']['search']; ?>
</h1>


			<div class="headline">
				<a href="javascript:void(0);" id="block_tags_toggle" class="win_block" onclick = "toggleBlock('block_tags');"></a>
				
				<h2>
					<img src="./templates/standard/images/symbols/search.png" alt="" /><?php echo $this->_config[0]['vars']['results']; ?>
 (<?php echo $this->_tpl_vars['num']; ?>
)
				</h2>
			</div>

			<div id="block_tags" class="blockwrapper">
			
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
							<?php unset($this->_sections['obj']);
$this->_sections['obj']['name'] = 'obj';
$this->_sections['obj']['loop'] = is_array($_loop=$this->_tpl_vars['result']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['obj']['show'] = true;
$this->_sections['obj']['max'] = $this->_sections['obj']['loop'];
$this->_sections['obj']['step'] = 1;
$this->_sections['obj']['start'] = $this->_sections['obj']['step'] > 0 ? 0 : $this->_sections['obj']['loop']-1;
if ($this->_sections['obj']['show']) {
    $this->_sections['obj']['total'] = $this->_sections['obj']['loop'];
    if ($this->_sections['obj']['total'] == 0)
        $this->_sections['obj']['show'] = false;
} else
    $this->_sections['obj']['total'] = 0;
if ($this->_sections['obj']['show']):

            for ($this->_sections['obj']['index'] = $this->_sections['obj']['start'], $this->_sections['obj']['iteration'] = 1;
                 $this->_sections['obj']['iteration'] <= $this->_sections['obj']['total'];
                 $this->_sections['obj']['index'] += $this->_sections['obj']['step'], $this->_sections['obj']['iteration']++):
$this->_sections['obj']['rownum'] = $this->_sections['obj']['iteration'];
$this->_sections['obj']['index_prev'] = $this->_sections['obj']['index'] - $this->_sections['obj']['step'];
$this->_sections['obj']['index_next'] = $this->_sections['obj']['index'] + $this->_sections['obj']['step'];
$this->_sections['obj']['first']      = ($this->_sections['obj']['iteration'] == 1);
$this->_sections['obj']['last']       = ($this->_sections['obj']['iteration'] == $this->_sections['obj']['total']);
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
												<?php if ($this->_tpl_vars['result'][$this->_sections['obj']['index']]['type'] == 'file'): ?>
													<a style="top:-33px;" href = "files/<?php echo $this->_tpl_vars['cl_config']; ?>
/<?php echo $this->_tpl_vars['result'][$this->_sections['obj']['index']]['project']; ?>
/<?php echo $this->_tpl_vars['result'][$this->_sections['obj']['index']]['name']; ?>
" <?php if ($this->_tpl_vars['result'][$this->_sections['obj']['index']]['imgfile'] == 1): ?> rel="lytebox[]" <?php elseif ($this->_tpl_vars['result'][$this->_sections['obj']['index']]['imgfile'] == 2): ?> rel = "lyteframe[text]" rev="width: 650px; height: 500px;" <?php endif; ?>>
														<?php if ($this->_tpl_vars['result'][$this->_sections['obj']['index']]['imgfile'] == 1): ?>
														<img src = "thumb.php?pic=<?php echo $this->_tpl_vars['result'][$this->_sections['obj']['index']]['datei']; ?>
&amp;width=32" alt="" />
														<?php else: ?>
														<img src = "templates/standard/images/symbols/<?php echo $this->_tpl_vars['result'][$this->_sections['obj']['index']]['icon']; ?>
" alt="" />
														<?php endif; ?>
													</a>	
												<?php else: ?>
													<a href = "<?php echo $this->_tpl_vars['result'][$this->_sections['obj']['index']]['url']; ?>
" title="<?php echo $this->_tpl_vars['result'][$this->_sections['obj']['index']]['name']; ?>
">
														<img src = "templates/standard/images/symbols/<?php echo $this->_tpl_vars['result'][$this->_sections['obj']['index']]['icon']; ?>
" alt="" />
													</a>
												<?php endif; ?>											
											</td>
											<td class="rightmen" valign="top">
												<!--
												<div class="inmenue">
													<a class="del" href="javascript:confirmfunction('<?php echo $this->_config[0]['vars']['confirmdel']; ?>
','deleteElement(\'files_focus<?php echo $this->_tpl_vars['ordner'][$this->_sections['file']['index']]['ID']; ?>
\',\'managefile.php?action=delete&amp;id=<?php echo $this->_tpl_vars['project']['ID']; ?>
&amp;file=<?php echo $this->_tpl_vars['folders'][$this->_sections['fold']['index']]['ID']; ?>
\')');" title="<?php echo $this->_config[0]['vars']['delete']; ?>
" onclick="fadeToggle('iw_<?php echo $this->_tpl_vars['folders'][$this->_sections['fold']['index']]['ID']; ?>
');"></a>
													<a class="edit" href="#" title="<?php echo $this->_config[0]['vars']['editfile']; ?>
"></a>
												</div>
												-->
											</td>
										</tr>
										<tr>
											<td colspan="3">
												<span class="name">
														<?php if ($this->_tpl_vars['result'][$this->_sections['obj']['index']]['type'] == 'file'): ?>
															<a href = "files/<?php echo $this->_tpl_vars['cl_config']; ?>
/<?php echo $this->_tpl_vars['result'][$this->_sections['obj']['index']]['project']; ?>
/<?php echo $this->_tpl_vars['result'][$this->_sections['obj']['index']]['name']; ?>
" <?php if ($this->_tpl_vars['result'][$this->_sections['obj']['index']]['imgfile'] == 1): ?> rel="lytebox[]" <?php elseif ($this->_tpl_vars['result'][$this->_sections['obj']['index']]['imgfile'] == 2): ?> rel = "lyteframe[text]" rev="width: 650px; height: 500px;" <?php endif; ?> title="<?php echo $this->_tpl_vars['result'][$this->_sections['obj']['index']]['name']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['obj']['index']]['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 13, "...", true) : smarty_modifier_truncate($_tmp, 13, "...", true)); ?>
</a>
														<?php elseif ($this->_tpl_vars['result'][$this->_sections['obj']['index']]['name'] != ""): ?>
															<a href = "<?php echo $this->_tpl_vars['result'][$this->_sections['obj']['index']]['url']; ?>
" title="<?php echo $this->_tpl_vars['result'][$this->_sections['obj']['index']]['name']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['obj']['index']]['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 13, "...", true) : smarty_modifier_truncate($_tmp, 13, "...", true)); ?>
</a>
														<?php else: ?>
															<a href = "<?php echo $this->_tpl_vars['result'][$this->_sections['obj']['index']]['url']; ?>
" title="<?php echo $this->_tpl_vars['result'][$this->_sections['obj']['index']]['title']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['result'][$this->_sections['obj']['index']]['title'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 13, "...", true) : smarty_modifier_truncate($_tmp, 13, "...", true)); ?>
</a>
														<?php endif; ?>
													</a>
												</span>
											</td>
										<tr/>
									</table>						

											<?php if ($this->_tpl_vars['members'][$this->_sections['member']['index']]['avatar'] != ""): ?>
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
			</div>
				
					
			<div class="tablemenue"></div>
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