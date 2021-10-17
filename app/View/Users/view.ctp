<p><?php echo $this->Html->link('Toppage', array('controller' => 'posts', 'action' => 'index')); ?></p>
<?php if ($auth['id'] === $user['User']['id']): ?>
<p><?php echo $this->Html->link('User Information Edit', array('controller' => 'users', 'action' => 'edit', $user['User']['id'])); ?></p>
<?php endif; ?>
<p><small>Created: <?php echo $user['User']['created']; ?></small></p>

<p>id: <?php echo h($user['User']['id']); ?></p>
<p>username: <?php echo h($user['User']['username']); ?></p>
<p>image: <?php echo ($this->Html->image($user['User']['image'], array('width' => '100', 'height' => '100'), array('alt' => 'CakePHP'))); ?></p>
<p>comment: <?php echo h($user['User']['comment']); ?></p>
