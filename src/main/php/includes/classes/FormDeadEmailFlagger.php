<?php

use \libAllure\Form;
use \libAllure\ElementInput;

class FormDeadEmailFlagger extends Form {
	public function __construct() {
		parent::__construct('deadEmailFlagger');

		$this->addElement(new ElementInput('email', 'Email'));

		$this->addButtons(Form::BTN_SUBMIT);
	}

	public function process() {
		global $db;

		$sql = 'SELECT email FROM users WHERE email = :email LIMIT 1';
		$stmt = $db->prepare($sql);
		$stmt->bindValue('email', $this->getElementValue('email'));
		$stmt->execute();

		if ($stmt->numRows() == 0) {
			$this->setElementError('email', 'Email not found.');

			return;
		}

		$sql = 'UPDATE users SET emailFlagged = 1 WHERE email = :email ';
		$stmt = $db->prepare($sql);
		$stmt->bindValue('email', $this->getElementValue('email'));
		$stmt->execute();
	}
}

?>
