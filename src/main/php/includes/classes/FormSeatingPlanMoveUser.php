<?php

use \libAllure\Form;
use \libAllure\ElementInput;
use \libAllure\ElementNumeric;
use \libAllure\Sanitizer;
use \libAllure\User;

require_once 'includes/functions.seatingPlan.php';

class FormSeatingPlanMoveUser extends Form {
	public function __construct() {
		parent::__construct('formSeatingPlanMoveUser', 'Move user');

		$eventId = Sanitizer::getInstance()->filterUint('event');

		$this->addElement(new ElementInput('username', 'Username'));
		$this->addElement(new ElementNumeric('seat', 'New seat number'));
		$this->addElementHidden('event', $eventId);

		$this->addDefaultButtons();
	}

	public function validateExtended() {
		try {
			$this->userId = User::getUser($this->getElementValue('username'))->getId();
		} catch (Exception $e) {
			$this->getElement('username')->setValidationError($e->getMessage());
		}
	}

	public function process() {
		$eventId = $this->getElementValue('event');
		$userId = $this->userId;
		$seatId = $this->getElementValue('seat');

		setSeatForUser($eventId, $userId, $seatId);
		logActivity('Moved user ' . $this->getElementValue('username') . ' to seat ' . $seatId);
	}
} 
