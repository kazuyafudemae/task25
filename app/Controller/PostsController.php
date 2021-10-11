<?php
App::uses('AppController', 'Controller');
class PostsController extends AppController {
	public $helpers = array('Html', 'Form', 'Flash');
	public $components = array('Flash', 'Auth');

	public function index() {
		$query = 'SELECT * FROM posts LEFT OUTER JOIN users ON posts.user_id = users.id ORDER BY posts.id';
		$this->set('posts', $this->Post->query($query));
		$this->set('auth', $this->Auth->user());
	}

	public function view($id = null) {
		if (!$id) {
			throw new NotFoundException(__('Invalid post'));
		}

		$post = $this->Post->findById($id);
		if (!$post) {
			throw new NotFoundException(__('Invalid post'));
		}
		$this->set('post', $post);
	}

	public function add() {
		if (!$this->Auth->User()) {
			return $this->redirect(array('controller' => 'users', 'action' => 'add'));
		}
		if ($this->request->is('post')) {
			$this->Post->create();
			$this->request->data['Post']['user_id'] = $this->Auth->user('id');
			if ($this->Post->save($this->request->data)) {
				$this->Flash->success(__('Your post has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Flash->error(__('Unable to add your post.'));
		}
	}

	public function edit($id = null) {
		if (!$id) {
			$this->Flash->error(__('Invalid user'));
			return $this->redirect(array('action' => 'index'));
		}

		$post = $this->Post->findById($id);
		if (!$post) {
			$this->Flash->error(__('IDと一致しませんでした'));
			return $this->redirect(array('action' => 'index'));
		}

		if ($post['Post']['user_id'] !== $this->Auth->user('id')) {
			$this->Flash->error(__('You are not author of this post'));
			return $this->redirect(array('action' => 'index'));
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->Post->id = $id;
			if ($this->Post->save($this->request->data)) {
				$this->Flash->success(__('Your post has been updated.'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Flash->error(__('Unable to update your post'));
			return $this->redirect(array('action' => 'index'));
		}

		if (!$this->request->data) {
			$this->request->data = $post;
		}
	}

	public function delete($id) {
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}
		$post = $this->Post->findById($id);
		if ($post['Post']['user_id'] !== $this->Auth->user('id')) {
			$this->Flash->error(__('You are not author of this post'));
			return $this->redirect(array('action' => 'index'));
		}
		if ($this->Post->delete($id)) {
			$this->Flash->success(__('The post with id: %s has been deleted.', h($id)));
			return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
		} else {
			$this->Flash->error(__('The post with id: %s could not be deleted.', h($id)));
			return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function isAuthorized($user) {
		if (in_array($this->action, array('edit', 'delete'))) {
			$postId = (int) $this->request->params['pass'][0];
			if ($this->Post->isOwnedBy($postId, $user['id'])) {
				return true;
			}
		}
		return parent::isAuthorized($user);
	}
}
?>
