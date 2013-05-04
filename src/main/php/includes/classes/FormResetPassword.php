<?php

use \libAllure\Form;
use \libAllure\ElementHtml;
use \libAllure\ElementInput;
use \libAllure\ElementEmail;
use \libAllure\ElementPassword;
use \libAllure\ElementHidden;

/*
Passwords can be reset in a few ways;

User: Provide email (1), we email them, they enter secret (2)
*/
class FormResetPassword extends Form {
	const STATE_USER_PROVIDE_EMAIL = 1;
	const STATE_USER_PROVIDE_SECRET = 2;

	private $state;

	public function __construct($state = null) {
		parent::__construct('forgotPasswordForm', 'Reset password');
	
		if ($state == null) {
			$this->state = Sanitizer::getInstance()->filterUint('state');
		} else {
			$this->state = $state;
		}

		$this->addElement(new ElementHidden('state', null, $this->state));

		switch ($this->state) {
			case self::STATE_USER_PROVIDE_EMAIL: 
				$this->constructUserProvideEmail(); 
				break;
			case self::STATE_USER_PROVIDE_SECRET: 
				$this->constructUserProvideSecret(); 
				break;
			default: throw new InvalidArgumentException('Unknown form state: ' . $this->state);	
		}

		$this->addButtons(Form::BTN_SUBMIT);
	}

	public function constructUserProvideEmail() {
		$this->addElement(new ElementHtml('html', null, 'Please enter the email address that you used to register an account. An email will be sent to you that contains a URL to reset your password.'));
		$this->addElement(new ElementEmail('email', 'Email'));

		$this->requireFields(array('email'));
	}

	public function constructUserProvideSecret() {
		$this->addElement(new ElementInput('secret', 'Secret code'));
		$this->addElement(new ElementPassword('password1', 'New password'));
		$this->addElement(new ElementPassword('password2', 'New password (confirm)'));

		$this->requireFields(array('secret'));
	}

	public function process() {
		switch ($this->state) {
			case self::STATE_USER_PROVIDE_EMAIL: 
				$this->processUserProvideEmail(); 
				break;
			case self::STATE_USER_PROVIDE_SECRET: 
				$this->processUserProvideSecret(); 
				break;
			default: 
				throw new InvalidArgumentException('Unknown form state');
		}

		throw new Exception('FormResetPassword::process() did not self terminate');
	}

	public function validateExtended() {
		global $db;

		switch ($this->state) {
			case self::STATE_USER_PROVIDE_EMAIL:
				$this->validateEmailAddressRegistered();
				break;
			case self::STATE_USER_PROVIDE_SECRET:
				$this->validateSecretCodeExists();
				$this->validateNewPasswordValid();
				break;		
		}
	}

	private function validateNewPasswordValid() {
		if ($this->getElementValue('password1') != $this->getElementValue('password2')) {
			$this->setElementError('password2', 'This password does not match the first.');
		}
	}

	private function validateEmailAddressRegistered() {
		global $db;

		$sql = 'SELECT u.username FROM users u WHERE u.email = :email ';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':email', $this->getElementValue('email'));
		$stmt->execute();

		if ($stmt->numRows() == 0) {
			$this->setElementError('email', 'This email address is not used by any user of the site.');
		}
	}

	private function validateSecretCodeExists() {
		global $db;

		$sql = 'SELECT u.username, u.id FROM users u WHERE u.passwordResetSecret = :secret';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':secret', $this->getElementValue('secret'));
		$stmt->execute();

		if ($stmt->numRows() == 0) {
			$this->setElementError('secret', 'That secret code is invalid.');
		} else {
			$this->userThatWillBeReset = $stmt->fetchRow();
		}
	}

	private function processUserProvideEmail() {
		global $db;

		$resetCode = uniqid();

		$sql = 'UPDATE users u SET u.passwordResetSecret = :resetCode WHERE u.email = :email LIMIT 1';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':resetCode', $resetCode);
		$stmt->bindValue(':email', $this->getElementValue('email'));
		$stmt->execute();

		$content = "Hey,\n\nYou asked for your password to be reset. Your reset code is: {$resetCode} \n\nGo to this page to complete the reset: " . getSiteSetting('baseUrl') . "/forgotPassword.php?state=2 \n\nIf you have any problems, contact us. Do not reply to this email.\n\n";

		sendEmail($this->getElementValue('email'), 'Password reset code', $content);

		echo '<div class = "box"><h2>Password reset email has been sent.</h2><p>Check your email.</p><a href = "forgotPassword.php?state=2">I have the secret code!</a></div>';
		require_once 'includes/widgets/footer.php';
	}

	private function processUserProvideSecret() {
		global $db;

		$sql = 'UPDATE users u SET u.password = :newPassword, u.passwordResetSecret = NULL WHERE u.passwordResetSecret = :secret LIMIT 1';
		$stmt = $db->prepare($sql);

		$password = sha1($this->getElementValue('password1') . CFG_PASSWORD_SALT);
		$stmt->bindValue(':newPassword', $password);
		$stmt->bindValue(':secret', $this->getElementValue('secret'));
		$stmt->execute();

		

		redirect('login.php?username=' . $this->userThatWillBeReset['username'], 'Password reset! You can now login.');
	}

}

?>
