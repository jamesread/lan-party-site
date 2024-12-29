<?php

use \libAllure\Form;
use \libAllure\ElementSelect;
use \libAllure\DatabaseFactory;

class FormUpdateFinanceAllocator extends Form {
    private $availableAccounts = array();
    private $allocatedPaymentTypes = array();

	public function __construct() {
		parent::__construct('formUpdateFinanceAllocator', 'Update allocator');	

		$this->allocatedPaymentTypes = array();

		$this->loadAvailableAccounts();
		$this->addStandardAccounts();
		$this->addPersonalAccounts();

		$this->addDefaultButtons();
	}	

	private function loadAvailableAccounts() {
		$sql = 'SELECT al.identifier, a.title, a.id FROM finance_account_allocations al RIGHT JOIN finance_accounts a ON al.account = a.id';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->execute();

		$this->availableAccounts = array();

		foreach ($stmt->fetchAll() as $account) {
			$this->availableAccounts[$account['identifier']] = $account;
		}
	}

	private function addStandardAccounts() {
		$this->addSection('Standard accounts');
		$this->addAccountAllocation('chequeAccount', 'Checking account');
		$this->addAccountAllocation('paypalAccount', 'Paypal account');
	}

	private function addPersonalAccounts() {
		$sql = 'SELECT u.username, u.id FROM users u WHERE u.`group` = 1'; 
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->execute();

		$this->addSection('Staff accounts');

		foreach ($stmt->fetchAll() as $user) {
			$this->addAccountAllocation('user' . $user['id'], $user['username'] . 's staff account');
		}
	}

	private function addAccountAllocation($paymentType, $title) {
		$el = new ElementSelect($paymentType, $title);

		foreach ($this->availableAccounts as $account) {
			$el->addOption($account['title'], $account['id']);
		}

		if (isset($this->availableAccounts[$paymentType])) {
			$el->setValue($this->availableAccounts[$paymentType]['id']);
		} else {
			$el->setvalue(1);
		}

		$this->allocatedPaymentTypes[] = array('paymentType' => $paymentType);
		$this->addElement($el);
	}

	public function process() {
		$sql = 'DELETE FROM finance_account_allocations';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->execute();

		$sql = 'INSERT INTO finance_account_allocations (identifier, account) values (:paymentType, :account)';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);

		foreach ($this->allocatedPaymentTypes as $account) {
			$stmt->bindValue(':paymentType', $account['paymentType']);
			$stmt->bindValue(':account', $this->getElementValue($account['paymentType']));
			$stmt->execute();
		}
	}
}

?>
