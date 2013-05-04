<?php

use \libAllure\Form;
use \libAllure\ElementInput;
use \libAllure\ElementNumeric;
use \libAllure\ElementHidden;
use \libAllure\Sanitizer;
use \libAllure\DatabaseFactory;

class FormCreateFinanceEntry extends Form {
	public function __construct() {
		parent::__construct('formCreateFinanceEntry', 'Create Finance Entry');

		$sanitizer = new Sanitizer();

		$this->addElement(new ElementInput('description', 'Description'));
		$this->addElement(new ElementNumeric('amount', 'Amount'));
		$this->addElement(new ElementHidden('account', 'Account', $sanitizer->filterUint('account')));

		$this->addDefaultButtons();
	}

	public function process() {
		$sql = 'INSERT INTO finance_transactions (amount, description, account) VALUES (:amount, :description, :account) ';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':amount', $this->getElementValue('amount'));
		$stmt->bindValue(':description', $this->getElementValue('description'));
		$stmt->bindValue(':account', $this->getElementValue('account'));
		$stmt->execute();

		redirect('viewFinanceAccount.php?id=' . $this->getElementValue('account'), 'Finance entry created.');
	}
}

?>
