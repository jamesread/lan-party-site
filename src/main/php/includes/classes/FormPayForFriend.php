<?php

use \libAllure\Form;
use \libAllure\ElementInput;
use \libAllure\ElementHidden;
use \libAllure\ElementButton;
use \libAllure\ElementSelect;
use \libAllure\User;
use \libAllure\DatabaseFactory;
use \libAllure\Session;

class FormPayForFriend extends Form {
	public function __construct($events) {
		parent::__construct('payForFriend', 'Pay for friend');

		$eventsSel = new ElementSelect('event', 'Event');

		foreach ($events as $event) {
			$eventsSel->addOption($event['name'], $event['id']);
		}

		$this->addElement($eventsSel);


		$this->addElement(new ElementInput('username', 'Your friends username'));

		$this->addElement(new ElementHidden('action', null, 'add'));
		$this->addDefaultButtons('Add friends ticket to basket');

	}

	function validateExtended() {
		$this->validateUsername();
	}

	private function validateUsername() {
		$username = $this->getElementValue('username');

		if (empty($username)) {
			$this->getElement('username')->setValidationError('You must enter a username.');
			return;
		}

		try {
			$this->user = User::getUser($this->getElementValue('username'));
		} catch (\libAllure\UserNotFoundException $e) {
			$this->setElementError('username', 'User not found');
			return;
		}

		$sql = 'SELECT bi.id FROM basket_items bi WHERE bi.user = :user AND bi.event = :event ';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':user', $this->user->getId());
		$stmt->bindValue(':event', $this->getElementValue('event'));
		$stmt->execute();

		if ($stmt->numRows() != 0) {
			$this->setElementError('username', 'That user already has a ticket in your basket!');
			return;
		}

		$sql = 'SELECT status FROM signups WHERE user = :user AND event = :event AND status != "SIGNEDUP" ';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':user', $this->user->getId());
		$stmt->bindValue(':event', $this->getElementValue('event'));
		$stmt->execute();

		if ($stmt->numRows() != 0) {
			$user = $stmt->fetchRow();
			$this->setElementError('username', 'This user is already signed up, with status ' . $user['status']);
			return;
		}
	}

	public function process() {
		$sql = 'INSERT INTO basket_items (user, event, basketOwner) VALUES (:user, :event, :basketOwner)';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':user', $this->user->getId());
		$stmt->bindValue(':event', $this->getElementValue('event'));
		$stmt->bindValue(':basketOwner', Session::getUser()->getId());
		$stmt->execute();
	}
}

?>
