<h1>Blor posts</h1>
<div>
<p><?php echo $this->Html->link('Add Post', array('controller' => 'posts', 'action' => 'add')); ?></p>
<?php if (!isset($auth)): ?>
<p><?php echo $this->Html->link('Login', array('controller' => 'users', 'action' => 'login')); ?></p>
<p><?php echo $this->Html->link('New Registration', array('controller' => 'users', 'action' => 'add')); ?></p>
<?php else: ?>
<p><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout')); ?></p>
<?php endif; ?>
<p><?php echo $this->Html->link('Users list', array('controller' => 'users', 'action' => '/')); ?></p>
<table>
<tr>
<th>Id</th>
<th>Username</th>
<th>Title</th>
<th></th>
<th></th>
<th>Body</th>
<th>Created</th>
</tr>

<?php debug($auth); ?>
<?php foreach ($posts as $post): ?>
<tr>
<td>
<?php echo $post['posts']['id']; ?>
</td>
<td>
<?php echo $this->Html->link($post['users']['username'], array('controller' => 'users', 'action' => 'view', $post['users']['id'])); ?>
</td>
<td>
<?php echo $this->Html->link($post['posts']['title'], array('controller' => 'posts', 'action' => 'view', $post['posts']['id'])); ?>
</td>
<td>
<?php if ($auth['id'] === $post['posts']['user_id']): ?>
<?php echo $this->Form->postLink('Delete', array('controller' => 'posts', 'action' => 'delete', $post['posts']['id']), array('confirm' => 'Are you sure?')); ?>
<?php endif; ?>
</td>
<td>
<?php if ($auth['id'] === $post['posts']['user_id']): ?>
<?php echo $this->Html->link('Edit', array('controller' => 'posts', 'action' => 'edit', $post['posts']['id'])); ?>
<?php endif; ?>
</td>
<td>
<?php echo $post['posts']['body']; ?>
</td>
<td>
<?php echo $post['posts']['created']; ?>
</td>
</tr>
<?php endforeach; ?>
<?php unset($post); ?>
</table>


