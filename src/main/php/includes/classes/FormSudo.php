<?php

use \libAllure\Form;
use \libAllure\ElementHtml;
use \libAllure\ElementInput;
use \libAllure\User;
use \libAllure\Session;

class FormSudo extends Form {
	public function __construct($username = null) {
		parent::__construct('formSudo', 'SUDO');

		if (!empty($_SESSION['userHidden'])) {
			$this->addElement(new ElementHtml('description', null, 'Submitting this form will return you to your user account...'));
		} else {
			$this->addElement(new ElementHtml('description', null, 'This form allows you to impersonate other users without having to know their password. It is useful for testing out their permissions.'));
			$this->addElement(new ElementInput('username', 'Username to login as'));

			if (!empty($username)) {
				$this->getElement('username')->setValue($username);
			}
		}

		$this->addDefaultButtons();
	}

	public function validateExtended() {
		if (empty($_SESSION['userHidden'])) {
			try {
				$this->user = User::getUser($this->getElementValue('username'));

				if ($this->user->getData('group') == 1) {
					$this->setElementError('username', 'You cannot SUDO into an admin account.');
				}
			} catch (\libAllure\UserNotFoundException $e) {
				$this->setElementError('username', 'Username not found');
			}
		}
	}

	public function process() {
		if (!empty($_SESSION['userHidden'])) {
			$_SESSION['user'] = $_SESSION['userHidden'];
			$_SESSION['userHidden'] = null;
		} else {
			// Directly manipulate the session to workaround the security restrictions.
			if ($this->user->getId() == Session::getUser()->getId()) {
				return;
			}

			$_SESSION['userHidden'] = $_SESSION['user'];
			$_SESSION['user'] = $this->user;
		}
	}
}

?>
