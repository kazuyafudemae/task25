<h1>Users List</h1>
<div><?php echo $this->Html->link('Toppage', array('controller' => 'posts', 'action' => 'index')); ?></div>
<table>
<tr>
<th>Id</th>
<th>Username</th>
<th>Created</th>
</tr>

<?php foreach ($users as $user): ?>
<tr>
<td><?php echo $user['User']['id']; ?></td>
<td><?php echo $user['User']['username']; ?></td>
<td><?php echo $user['User']['created']; ?></td>
</tr>
<?php endforeach; ?>
</table>

