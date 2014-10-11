<?php

use \libAllure\Form;
use \libAllure\ElementSelect;
use \libAllure\DatabaseFactory;

class FormPayTicketCash extends Form {
	public function __construct() {
		parent::__construct('payTicketCash', 'Pay for ticket with cash');

		$this->addElement($this->getElementUsername());

		$this->addDefaultButtons();
	}

	private function getElementUsername() {
		$el = new ElementSelect('username', 'Who did you give the money to?');

		$sql = 'SELECT a.id, u.username FROM finance_account_allocations al JOIN finance_accounts a ON al.account = a.id JOIN users u ON a.assigned_to = u.id ';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->execute();
		
		foreach ($stmt->fetchAll() as $account) {
			$el->addOption($account['username'], $account['id']);
		}

		return $el;
	}

	public function process() {
		$sql = 'INSERT INTO finance_transactions (amount, description, timestamp, account) VALUES (:amount, :title, now(), :account) ';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);

		foreach (Basket::getContents() as $basketItem) {
			$stmt->bindValue(':amount', $basketItem['cost']);
			$stmt->bindValue(':title', '(given cash) ' . $basketItem['title'] . ' ticket for ' . $basketItem['username']);
			$stmt->bindValue(':account', $this->getElementValue('username'));
			$stmt->execute();			

			Events::setSignupStatus($basketItem['userId'], $basketItem['eventId'], 'CASH_IN_POST');
		}
	}
}

?>
