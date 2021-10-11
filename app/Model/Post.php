<?php
class Post extends AppModel {
	public $validate = array(
		'title' => array(
			'rule' => 'notBlank',
			'rule' => array('maxLength', '30'),
			'message' => '30字以内で入力してください'
		),
		'body' => array(
			'rule' => 'notBlank',
			'rule' => array('maxLength', '100'),
			'message' => '100字以内で入力してください'
		)
	);
	public function isOwnedBy($post, $user) {
		return $this->field('id', array('id' => $post, 'user_id' => $user)) !== false;
	}
}
?>
