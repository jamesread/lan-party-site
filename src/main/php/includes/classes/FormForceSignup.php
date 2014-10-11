<?php

use \libAllure\Form;
use \libAllure\ElementInput;
use \libAllure\ElementHidden;
use \libAllure\User;
use \libAllure\Session;

class FormForceSignup extends Form {
	public function __construct($eventId) {
		parent::__construct('forceSignup', 'Force Signup');

		$this->addElement(new ElementInput('username', 'Username'));
		$this->addElement(new ElementHidden('id', null, $eventId));

		$this->addButtons(Form::BTN_SUBMIT);
		$this->getElement('submit')->setCaption('Force signup');
	}

	public function process() {
		$event = Events::getById($this->getElementValue('id'));

		Events::setSignupStatus($this->user->getId(), $event['id'], 'SIGNEDUP');
		Events::appendSignupComment($this->user->getId(), $event['id'], 'Forced signup.', Session::getUser()->getUsername());

		logActivity('Forced signup of:' . $this->getElementValue('username') . ' to event: ' . $event['id'] . ' (' . $event['name'] . ')');

		redirect('viewEvent.php?id=' . $event['id'], 'They have been signed up.');
	}

	private $user;

	public function validateExtended() {
		try {
			$this->user = User::getUser($this->getElementValue('username'));
		} catch (\libAllure\UserNotFoundException $e) {
			$this->setElementError('username', 'User not found!');

			return false;
		}

		return true;
	}
}

?>
