<?php

use \libAllure\Form;
use \libAllure\ElementInput;
use \libAllure\ElementNumeric;
use \libAllure\Sanitizer;
use \libAllure\User;

class FormSwapSeats extends \libAllure\Form {
	public function __construct() {
		parent::__construct('swapSeats', 'Swap Seats');

		requirePrivOrRedirect('SWAP_USERS_SEATS');

		$this->eventId = Sanitizer::getInstance()->filterUint('event');

		$this->addElement(new ElementInput('username1', 'First username'));
		$this->addElement(new ElementInput('username2', 'Second username'));
		$this->addElementHidden('event', $this->eventId);

		$this->addDefaultButtons();
	}

	public function validateExtended() {
		try {
			$this->user1 = User::getUser($this->getElementValue('username1'))->getId();
		} catch (Exception $e) {
			$this->getElement('username1')->setValidationError($e->getMessage());
		}

		try {
			$this->user2 = User::getUser($this->getElementValue('username2'))->getId();
		} catch (Exception $e) {
			$this->getElement('username2')->setValidationError($e->getMessage());
		}
	}


	function process() {
//		var_dump($this->eventId, $this->user1, $this->user2); exit;
		swapUsersSeats($this->eventId, $this->user1, $this->user2);
	}
}

?>
