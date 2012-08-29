<?php /* Smarty version 2.6.19, created on 2012-08-29 14:02:56
         compiled from tabsmenue-project.tpl */ ?>

<div class="tabswrapper">
	<ul class="tabs">
		<li class="projects"><a <?php if ($this->_tpl_vars['projecttab'] == 'active'): ?>class="active"<?php endif; ?> href="manageproject.php?action=showproject&amp;id=<?php echo $this->_tpl_vars['project']['ID']; ?>
"><span><?php echo $this->_config[0]['vars']['project']; ?>
</span></a></li>

		<li class="miles"><a <?php if ($this->_tpl_vars['milestab'] == 'active'): ?>class="active"<?php endif; ?> href="managemilestone.php?action=showproject&amp;id=<?php echo $this->_tpl_vars['project']['ID']; ?>
"><span><?php echo $this->_config[0]['vars']['milestones']; ?>
</span></a></li>

		<li class="tasks"><a <?php if ($this->_tpl_vars['taskstab'] == 'active'): ?>class="active"<?php endif; ?> href="managetask.php?action=showproject&amp;id=<?php echo $this->_tpl_vars['project']['ID']; ?>
"><span><?php echo $this->_config[0]['vars']['tasklists']; ?>
</span></a></li>
		<li class="msgs"><a <?php if ($this->_tpl_vars['msgstab'] == 'active'): ?>class="active"<?php endif; ?> href="managemessage.php?action=showproject&amp;id=<?php echo $this->_tpl_vars['project']['ID']; ?>
"><span><?php echo $this->_config[0]['vars']['messages']; ?>
</span></a></li>
		<li class="files"><a <?php if ($this->_tpl_vars['filestab'] == 'active'): ?>class="active"<?php endif; ?> href="managefile.php?action=showproject&amp;id=<?php echo $this->_tpl_vars['project']['ID']; ?>
"><span><?php echo $this->_config[0]['vars']['files']; ?>
</span></a></li>
		<li class="user"><a <?php if ($this->_tpl_vars['userstab'] == 'active'): ?>class="active"<?php endif; ?> href="manageuser.php?action=showproject&amp;id=<?php echo $this->_tpl_vars['project']['ID']; ?>
"><span><?php echo $this->_config[0]['vars']['user']; ?>
</span></a></li>
		<li class="timetrack"><a <?php if ($this->_tpl_vars['timetab'] == 'active'): ?>class="active"<?php endif; ?> href="managetimetracker.php?action=showproject&amp;id=<?php echo $this->_tpl_vars['project']['ID']; ?>
"><span><?php echo $this->_config[0]['vars']['timetracker']; ?>
</span></a></li>
	</ul>
</div>