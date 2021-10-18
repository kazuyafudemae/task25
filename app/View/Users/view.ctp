<p><?php echo $this->Html->link('Toppage', array('controller' => 'posts', 'action' => 'index')); ?></p>
<?php if ($auth['id'] === $user['User']['id']): ?>
<p><?php echo $this->Html->link('User Information Edit', array('controller' => 'users', 'action' => 'edit', $user['User']['id'])); ?></p>
<?php endif; ?>
<p><small>Created: <?php echo $user['User']['created']; ?></small></p>

<p>id: <?php echo h($user['User']['id']); ?></p>
<p>username: <?php echo h($user['User']['username']); ?></p>
<?php if ($user['User']['image'] === NULL): ?>
<p>image: 未登録</p>
<?php else: ?>
<p>image: <?php echo ($this->Html->image($user['User']['image'], array('width' => '100', 'height' => '100'), array('alt' => 'CakePHP'))); ?></p>
<?php endif; ?>
<?php if ($user['User']['comment'] === NULL): ?>
<p>comment: 未登録</p>
<?php else: ?>
<p>comment: <?php echo h($user['User']['comment']); ?></p>
<?php endif; ?>
