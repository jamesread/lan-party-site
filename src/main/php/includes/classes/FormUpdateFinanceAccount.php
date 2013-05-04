<?php

use \libAllure\Form;
use \libAllure\ElementInput;
use \libAllure\ElementNumeric;
use \libAllure\ElementHidden;
use \libAllure\Sanitizer;
use \libAllure\DatabaseFactory;
use \libAllure\User;

class FormUpdateFinanceAccount extends Form {
	public function __construct() {
		parent::__construct('formUpdateFinanceAccount', 'Update Finance Account');

		$this->databaseRow = $this->getDatabaseRow();

		$this->addElement(new ElementInput('title', 'Title', $this->databaseRow['title']));
		$this->addElement(new ElementNumeric('assignedTo', 'Assigned to user ID', $this->databaseRow['assigned_to'], 'The user ID to assign this account to. You can type a username here to look up a user ID.'));
		$this->getElement('assignedTo')->setBounds(0, 999);
		$this->addElement(new ElementHidden('id', null, $this->databaseRow['id']));
		$this->addDefaultButtons();
	}

	protected function validateExtended() {
		$this->validateUsername();
	}

	private function validateUsername() {
		if (!is_numeric($this->getElementValue('assignedTo'))) {
			try {
				$user = User::getUser($this->getElementValue('assignedTo'));

				$this->getElement('assignedTo')->setValue($user->getId());
			} catch (\libAllure\UserNotFoundException $e) {
				$this->setElementError('assignedTo', 'Username not found.');
			}
		}
	}

	public function getDatabaseRow() {
		$sanitizer = new Sanitizer();

		$stmt = DatabaseFactory::getInstance()->prepareSelectById('finance_accounts', $sanitizer->filterUint('id'), 'title', 'assigned_to');
		$stmt->execute();

		return $stmt->fetchRowNotNull();
	}

	public function process() {
		$sql = 'UPDATE finance_accounts SET title = :title, assigned_to = :assignedTo WHERE id = :id';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':id', $this->getElementValue('id'));
		$stmt->bindValue(':title', $this->getElementValue('title'));
		$stmt->bindValue(':assignedTo', $this->getElementValue('assignedTo'));
		$stmt->execute();

		redirect('listFinanceAccounts.php', 'Updated');
	}
}

?>