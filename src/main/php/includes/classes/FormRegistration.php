<?php

use \libAllure\Form;
use \libAllure\ElementInput;
use \libAllure\ElementPassword;
use \libAllure\ElementAlphaNumeric;
use \libAllure\ElementEmail;

class FormRegistration extends Form {
	public function __construct() {
		parent::__construct('registration', 'Registration');

		$this->addSection('Account');
		$this->addElement(new ElementAlphaNumeric('username', 'Username'));
		$this->addElement(new ElementPassword('password1', 'Password'));
		$this->addElement(new ElementPassword('password2', 'Password (confirm)'));

		$this->addSection('Personal stuff');
		$this->addElement(new ElementEmail('email', 'Email Address', null, 'We will be really careful with your email address! '));
		$this->addElement(new ElementAlphaNumeric('realName', 'Real Name'));
		$this->getElement('realName')->setMinMaxLengths(0, 32);

		$this->addElement(new ElementAlphaNumeric('mobile', 'Mobile phone number', null, 'The reason we ask for your mobile phone number is to call you on the day if an event is cancelled, such as the hall flooding, burning down, zombie invasion, etc.'));
		$this->getElement('mobile')->setPattern('#^[\d ]+$#', 'numbers and spaces');
		$this->getElement('mobile')->setMinMaxLengths(11, 15);
		$this->addDefaultButtons();

		$this->requireFields(array('username', 'password1', 'email', 'realName'));
	}

	protected function validateExtended() {
		$this->validateUsername();
		$this->validateEmail();
		$this->validatePasswords();
	}

	private function validatePasswords() {
		if ($this->getElementValue('password1') != $this->getElementValue('password2')) {
			$this->setElementError('password2', 'Your passwords did not match.');
		}
	}

	public function validateEmail() {
		global $db;

		$sql = 'SELECT email FROM users WHERE email = :email LIMIT 1';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':email', $this->getElementValue('email'));
		$stmt->execute();

		if ($stmt->numRows() != 0) {
			$this->setElementError('email', 'That address is already in use by another member.');
		}
	}


	public function validateUsername() {
		global $db;

		$sql = 'SELECT username FROM users WHERE username = :username LIMIT 1';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':username', $this->getElementValue('username'));
		$stmt->execute();

		if ($stmt->numRows() != 0) {
			$this->setElementError('username', 'That username is already taken.');
		}
	}

	public function process() {
		global $db;

		$sql = 'INSERT INTO users (username, password, email, real_name, registered, mobileNo) VALUES (:username, :password, :email, :realname, now(), :mobile) ';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':username', $this->getElementValue('username'));

		$password = sha1($this->getElementValue('password1') . CFG_PASSWORD_SALT);

		$stmt->bindValue(':password', $password);
		$stmt->bindValue(':email', $this->getElementValue('email'));
		$stmt->bindValue(':realname', $this->getElementValue('realName'));
		$stmt->bindValue(':mobile', $this->getElementValue('mobile'));
		$stmt->execute();
	}
}

?>
