<div class='users form'>
<?php echo $this->Html->link('Toppage', array('controller' => 'posts', 'action' => 'index')); ?>
<?php echo $this->Form->create('User'); ?>
<fieldset>
<legend><?php echo (__('Add User')); ?></legend>
<?php
echo $this->Form->input('username');
echo $this->Form->input('email');
echo $this->Form->input('password');
?>
</fieldset>
<?php echo $this->Form->submit(__('Submit')); ?>
</div>
