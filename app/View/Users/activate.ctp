<div class='users form'>
<?php echo $this->Html->link('Toppage', array('controller' => 'posts', 'action' => 'index')); ?>
<?php echo $this->Form->create('User'); ?>
<fieldset>
<legend><?php echo (__('password registration')); ?></legend>
<?php
echo $this->Form->input('password');
?>
</fieldset>
<?php echo $this->Form->submit(__('Submit')); ?>
</div>
