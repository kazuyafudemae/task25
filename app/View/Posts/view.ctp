<?php echo $this->Html->link('Toppage', array('action' => 'index')); ?>

<h1>Title:<?php echo h($post['Post']['title']); ?></h1>
<p>Body:<?php echo h($post['Post']['body']); ?></p>
<p><small>Created: <?php echo $post['Post']['created']; ?></small></p>
