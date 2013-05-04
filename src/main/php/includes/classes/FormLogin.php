<?php

use \libAllure\Form;
use \libAllure\ElementHtml;
use \libAllure\ElementInput;
use \libAllure\ElementPassword;
use \libAllure\User;

class FormLogin extends Form {
	public function __construct() {
		parent::__construct('login', 'Login');

		$this->addElement(new ElementHtml('loginPasswordSecurity', null, getContent('loginPasswordSecurity')));
		$this->addElement(new ElementInput('username', 'Username'));

		$this->addElement(new ElementPassword('password', 'Password'));

		$this->addButtons(Form::BTN_SUBMIT);
	}

	private function validateUserBan(User $user) {
		$bannedReason = $user->getData('bannedReason');

		if (!empty($bannedReason)) {
			$this->setElementError('username', 'You are banned; ' . $bannedReason);
		}
	}

	private function validateSiteQuiesse(User $user) {
		if (getSiteSetting('maintenanceMode') && !$user->hasPriv('SUPERUSER')) {
			$this->setElementError('username', 'The site is down for maintenance.');
		}
	}

	public function validateExtended() {
		try {
			$user = User::getUser($this->getElementValue('username'));
		} catch (\libAllure\UserNotFoundException $e) {
			$this->getElement('username')->setValidationError('Username not found');
			return;
		}

		$this->validateSiteQuiesse($user);
		$this->validateUserBan($user);
	}
}

?>
