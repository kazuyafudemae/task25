<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

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

		if (!$this->request->data) {
			$this->request->data = $this->User->findById($this->Auth->user(['id']));
		}

		if ($this->request->is(array('post', 'put'))) {
			$image = $this->User->findById($id)['User']['image'];
			$file = $this->request->data['User']['image'];
			$original_filename = $file['name'];
			$uploaded_filename = $file['tmp_name'];

			if (!empty($original_filename)) {
				$image = uniqid(mt_rand(), true);
				switch (exif_imagetype($uploaded_filename)) {
					case 1:
						$image .= '.gif';
						break;
					case 2:
						$image .= '.jpg';
						break;
					case 3:
						$image .= '.png';
						break;
					default:
						$image = 'error';
						break;
				}
				if ($image !== 'error') {
					move_uploaded_file($uploaded_filename, '../webroot/img/' . $image);
					if (file_exists('../webroot/img/' . $this->User->findById($id)['User']['image'])) {
						@unlink('../webroot/img/' . $this->User->findById($id)['User']['image']);
					}
				} else {
					$this->Flash->error(__('ファイルの形式が適していませんでした。再度アップロードしてください'));
					return $this->redirect(array('controller' => 'users', 'action' => 'view', $id));
				}
			}

			if (empty($this->request->data['User']['comment'])) {
				$comment = null;
			} else {
				$comment = $this->request->data['User']['comment'];
			}

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

			if ($user_save) {
				$this->Flash->success(__('編集完了しました'));
				return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
			} else {
				$this->Flash->error(__('編集できませんでした。再度入力してください。'));
				return $this->redirect(array('controller' => 'users', 'action' => 'view', $id));
			}
		}
	}

	public function reset() {
		if($this->request->is(array('post', 'put'))) {
			$email = $this->request->data['User']['email'];
			$pass = $this->User->findByEmail($email);
			if ($pass) {
				$activation_code = uniqid(mt_rand(), true);
				$user_save = $this->User->save(
					array(
						'User' => array(
							'id' => $pass['User']['id'],
							'pass_reset_id' => $activation_code,
							'pass_reset_date' => date('Y-m-d H:i:s')
						),
						'fieldList' => array('pass_reset_id', 'pass_reset_date')
					)
				);

				$cakeemail = new CakeEmail('default');
				$cakeemail->to($email);
				$cakeemail->subject('パスワード再設定のお知らせ');
				$cakeemail->send('パスワードの再設定はこちらのURLから。有効期間は' . date('Y-m-d H:i:s', strtotime('30 minute')) . 'までです。https://procir-study.site/Fudemae225/task25/cakephp/users/activate/' . $activation_code);

				if ($user_save) {
					$this->Flash->success(__('再発行用URLを送信しました。'));
					return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
				} else {
					$this->Flash->error(__('再発行URLを送信できませんでした。再度お試しください。'));
					return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
				}
			} else {
				$this->Flash->success(__('再発行用URLを送信しました。'));
				return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
			}
		}
	}

	public function activate($activation_code) {
		$user = $this->User->findByPass_reset_id($activation_code);

		if ($this->request->is(array('post', 'put'))) {
			if (!$user) {
				$this->Flash->error(__('不正なアクセスです。再度お試しください'));
				return $this->redirect(array('controller' => 'users', 'action' => 'reset'));
			}

			$limit_time = date('Y-m-d H:i:s', strtotime(' - 30 minute'));
			if (strtotime($user['User']['pass_reset_date']) < strtotime($limit_time)) {
				$this->Flash->error(__('不正なアクセスです。再度お試しください'));
				return $this->redirect(array('controller' => 'users', 'action' => 'reset'));
			}

			$data = $this->request->data['User'];
			$user_save = $this->User->save(
				array(
					'User' => array(
						'id' => $user['User']['id'],
						'password' => $data['password'],
						'pass_reset_id' => null,
						'pass_reset_date' => null
					),
					'fieldList' => array('password', 'pass_reset_id', 'pass_reset_date')
				)
			);

			if ($user_save) {
				$this->Flash->success(__('パスワードを更新しました。'));
				return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
			} else {
				$this->Flash->error(__('パスワードの更新に失敗しました。再度お試しください。'));
			}
		}
	}
}

