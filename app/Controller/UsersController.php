<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('add', 'index', 'view');
	}

	public function index() {
		$this->set('users', $this->User->find('all'));
	}

	public function view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->findById($id));
	}

	public function login() {
		if ($this->Auth->user()) {
			$this->Flash->error(__('You cannot do this action while logging in.'));
			return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$this->redirect($this->Auth->redirect());
			} else {
				$this->Flash->error(__('Invalid username or password, try again'));
			}
		}
	}

	public function logout() {
		$logoutUrl = $this->Auth->logout();
		$this->redirect($this->Auth->redirect());
	}

	public function add() {
		if ($this->Auth->user()) {
			$this->Flash->error(__('You cannot do this action while logging in.'));
			return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Flash->success(__('The user has been saved'));
				return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
			}
			$this->Flash->error(__('The user could not be saved. Please, try again.'));
		}
	}

	public function delete($id = null) {
		$this->request->allowMethod('post');
		$this->User->id = $id;
		if (!$this->User->exists()) {
			$this->Flash->error(__('Invalid User'));
			return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}
		if ($this->User->delete()) {
			$this->Flash->success(__('User deleted'));
			return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}
		$this->Flash->error(__('The user could not be deleted. Please, try again.'));
		return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
	}
	public function edit($id = null) {
		if ($id === null) {
			$this->Flash->error(__('Invalid user'));
			return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}

		if ($id !== $this->Auth->user('id')) {
			$this->Flash->error(__('IDと一致しませんでした'));
			return $this->redirect(array('controller' => 'user', 'action' => 'view', $id));
		}

			debug($this->request->data);
		if ($this->request->is(array('post', 'put'))) {
			$uniqid = uniqid(mt_rand(), true);
			$file = $this->request->data['User']['image'];
			$original_filename = $file['name'];
			$uploaded_filename = $file['tmp_name'];
			debug($file);
			debug($original_filename);

				//getimagesize関数で拡張子が変更されていないか判別、サイズも見れる
			if ($original_filename != null) {
				if (!getimagesize($uploaded_filename)) {
					$this->Flash->error(__('編集されたファイルです'));
					return $this->redirect(array('action' => 'view', $id));
				}
				move_uploaded_file($uploaded_filename, '../webroot/img/' . $uniqid);
				$image = $uniqid . '.' . substr(strrchr($file['name'], '.'), 1);

							//ディレクトリにファイル保存
			} else {
				$image = $this->User->findById($id)['User']['image'];
			}
			if ($this->request->data['User']['comment'] === null) {
				$comment = $this->User->findById($id)['User']['comment'];
			} else {
				$comment = $this->request->data['User']['comment'];
			}
			debug($image);
			debug($comment);
			$user_save = $this->User->save(
				array(
					'User' => array(
						'id' => $this->Auth->user('id'),
						'image' => $image,
						'comment' => $comment
					),
					'fieldList' => array('image', 'comment')
				)
			);
/*
			$post = $this->Post->findById($id);
			if (!$this->request->data) {
				$this->request->data = $post;
			}
 */
			if ($user_save) {
				$this->Flash->error(__('編集完了しました'));
				return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
			} else {
				$this->Flash->error(__('編集できませんでした。再度入力してください。'));
				return $this->redirect(array('controller' => 'users', 'action' => 'edit', $id));
			}
		}
	}
}

