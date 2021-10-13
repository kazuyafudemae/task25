<?php echo $this->Html->link('Toppage', array('controller' => 'posts', 'action' => 'index')); ?>
<h1>Edit Information</h1>
<?php
echo $this->Form->create('User', array('enctype' => 'multipart/form-data'));
echo $this->Form->input('image', array('label' => '画像アップロード', 'type' => 'file'));
echo $this->Form->input('comment');
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->end('アップロード');
?>
