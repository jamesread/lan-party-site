<?php

use \libAllure\Form;
use \libAllure\ElementInput;
use \libAllure\DatabaseFactory;

class FormCreateFinanceAccount extends Form {
	public function __construct() {
		parent::__construct('formCreateFinanceAccount', 'Create finance account');

		$this->addElement(new ElementInput('title', 'Title'));

		$this->addDefaultButtons();
	}

	public function process() {
		$sql = 'INSERT INTO finance_accounts (title) VALUES (:title) ';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':title', $this->getElementValue('title'));
		$stmt->execute();

		redirect('listFinanceAccounts.php', 'Created');
	}
}

?>
