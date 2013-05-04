<?php

use \libAllure\Form;
use \libAllure\ElementInput;
use \libAllure\ElementPassword;

class FormUserRegistration extends Form {
	public function __construct() {
		parent::__construct('registration', 'Registration');

		$this->addElement(new ElementInput('username', 'Username'));
		$this->addElement(new ElementPassword('password1', 'Password'));
		$this->addElement(new ElementPassword('password2', 'Password (confirm)'));
		$this->addElement(new ElementInput('email', 'Email Address'));
		$this->addElement(new ElementInput('realName', 'Real Name'));
		$this->addDefaultButtons();

		$this->requireFields(array('username', 'password1', 'email', 'realName'));
	}

	protected function validateExtended() {
		global $db;

		$sql = 'SELECT username FROM users WHERE username = :username LIMIT 1';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':username', $this->getElementValue('username'));
		$stmt->execute();

		if ($stmt->numRows() != 0) {
			$this->setElementError('username', 'That username is already taken.');

			return false;
		}

		if ($this->getElementValue('password1') != $this->getElementValue('password2')) {
			$this->setElemenetError('password2', 'Your passwords did not match.');

			return false;
		}

		return true;
	}

	public function process() {
		global $db;

		$sql = 'INSERT INTO users (username, password, email, real_name) VALUES (:username, :password, :email, :realname) ';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':username', $this->getElementValue('username'));

		$password = sha1($this->getElementValue('password1') . CFG_PASSWORD_SALT);

		$stmt->bindValue(':password', $password);
		$stmt->bindValue(':email', $this->getElementValue('email'));
		$stmt->bindValue(':realname', $this->getElementValue('realName'));
		$stmt->execute();
	}
}

?>
