<?php

use \libAllure\Form;
use \libAllure\ElementHidden;
use \libAllure\ElementInput;

class FormSignup extends Form {
	public function __construct($event, $user, $status) {
		parent::__construct('signup', 'Signup to: ' . $event['name']);

		$this->addElement(new ElementHidden('user', null, $user));
		$this->addElement(new ElementHidden('event', null, $event['id']));

		$this->addElement(new ElementInput('comment', 'Special requirements', null, 'Let us know about things like really large monitors, friends you want to sit next to or anything else. If you dont have any special requirements it is customary to enter something funny for the staff to read ;)'));

		$this->addDefaultButtons();
	}

	public function process() {
		Events::setSignupStatus(
			$this->getElementValue('user'),
			$this->getElementValue('event'),
			'SIGNEDUP'
		);

		$userReq = $this->getElementValue('comment');

		if (!empty ($userReq)) {
			$userReq = 'User requirement: ' . $userReq;
		}

		Events::appendSignupComment(
			$this->getElementValue('user'),
			$this->getElementValue('event'),
			'User self signup. ' . $userReq
		);
	}
}

?>
