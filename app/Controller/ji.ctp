
	://us04web.zoom.us/j/76082201374?pwd=SFY0cXpEZlVtVkt0THg2d3BMMS9KZz09*
		if ($this->request->is('post')) {
			debug($this->request->data['User']);
			if ($this->request->data) { $file = $this->request->data['User']['image'];
				$original_filename = $file['name'];
				$uploaded_file = $file['tmp_name'];
				$filetype = $file['type'];
				if (!empty($original_filename)) {
					$image = uniqid(mt_rand(), true);
					switch (exif_imagetype($uploaded_file)) {
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
						$fullpath = WWW_ROOT . 'files/';
						$Auth = $this->Auth->user();
						move_uploaded_file($uploaded_file, $fullpath . $image);
						/*jj
						if (file_exists($fullpath . $Auth['image'])) {
							unlink($fullpath . $Auth['image']);
						}
					} else {
						$image = null;
					}
				} else {
					$image = null;
				}
				if ($image === null) {
					$image = $this->Auth->user('image');
				}
				if ($this->request->data['User']['comment'] === null) {
					$comment = $this->Auth->User('comment');
				} else {
					$comment = $this->request->data['User']['comment'];
				}
				debug($comment);
				debug($image);
				$user = $this->User->save(
					array(
						'User' => array(
							'id' => $this->Auth->user('id'),
							'image' => $image,
							'comment' => $comment
						),
						'fieldList' => array('image', 'comment')
					)
				);
				if ($user !== false) {
					$this->Flash->success(__('This infomation has been saved'));
					return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
				}
			}
			$this->Flash->error(__('This information could not be saved. Please, try again.'));
		}
	}
 */
}
