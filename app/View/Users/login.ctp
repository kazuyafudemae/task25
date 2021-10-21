<div><?php echo $this->Html->link('Toppage', array('controller' => 'posts', 'action' => 'index')); ?></div>
<div class 'users form'>
<?php
echo $this->Flash->render('auth');
echo $this->Form->create('User');
?>
<fieldset>
<legend>
<?php echo __('Please enter your email and password'); ?>
</legend>
<?php
echo $this->Form->input('email');
echo $this->Form->input('password');
?>
</fieldset>
<?php echo $this->Form->end(__('Login')); ?>
<div><?php echo $this->Html->link('パスワードを忘れた方はこちら', array('controller' => 'users', 'action' => 'reset')); ?></div>
</div>
