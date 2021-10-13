<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {
	public $validate = array(
		'username' => array(
			array(
				'rule' => 'notBlank',
				'message' => 'A username was required'
			),
			array(
				'rule' => 'isUnique',
				'message' => 'This username is not available'
			),
			array(
				'rule' => array('maxLength', 20),
				'message' => 'please input 100 characters or less'
			)
		),
		'email' => array(
			array(
				'rule' => array('custom', '/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9._-]+)+$/'),
				'message' => 'The format of the email is incorrect'
			),
			array(
				'rule' => 'isUnique',
				'message' => 'This email address is not available'
			)
		),
		'password' => array(
			array(
				'rule' => 'notBlank',
				'message' => 'A password is required'
			),
			array(
				'rule' => array('maxLength', 20),
				'message' => 'please input 20 characters or less'
			),
			array(
				'rule' => array('minLength', 8),
				'message' => 'Your password must be at least 8 characters'
			)
		),
		'image' => array(
			array(
				'rule' => array(
					'extension', array(
						'jpg',
						'jpeg',
						'gif',
						'png'
					)
				),
				'message' => '画像ではありません',
				'allowEmpty' => true
			),
			array(
				'rule' => array(
					'fileSize', '<=', '5000000'
				),
				'message' => '画像サイズは5MBが上限です',
				'allowEmpty' => true
			)
		),
		'comment' => array(
			array(
				'rule' => array('maxLength', 50),
				'message' => '50文字以内で入力してください',
				'allowEmpty' => true
			)
		)
	);

	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$passwordHasher = new BlowfishPasswordHasher();
			$this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
		}
		return true;
	}
}
?>
