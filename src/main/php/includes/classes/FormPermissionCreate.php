<?php

use \libAllure\Form;
use \libAllure\ElementAlphaNumeric;
use \libAllure\ElementInput;
use \libAllure\Session;

class FormPermissionCreate extends Form {
	public function __construct() {
		parent::__construct('formPermissionCreate', 'Create permission');
		Session::requirePriv('SUPERUSER');
		$this->addElement(new ElementAlphaNumeric('permission', 'Permission name', null, 'Even after creating a permission, it needs to be implemented in code for it to take affect.'));
		$this->getElement('permission')->setPatternToIdentifier();
		$this->addElement(new ElementInput('description', 'Description'));
		$this->getElement('description')->setMinMaxLengths(0, 2555);
		$this->addButtons(Form::BTN_SUBMIT);
	}

	public function validateExtended() {
		$this->validateKeyIsUnique();
	}	

	private function validateKeyIsUnique() {
		global $db;

		$sql = 'SELECT p.`key` FROM permissions p WHERE p.`key` = :newKeyCandidate LIMIT 1';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':newKeyCandidate', $this->getElementValue('permission'));
		$stmt->execute();

		if ($stmt->numRows() > 0) {
			$this->getElement('permission')->setValidationError('A permission with that name already exists.');
		}
	}

	public function process() {
		global $db;

		$sql = 'INSERT INTO permissions (`key`, `description`) VALUES (:key, :description) ';
		$stmt = $db->prepare($sql);
		$stmt->bindValue('key', strtoupper($this->getElementValue('permission')));
		$stmt->bindValue('description', $this->getElementValue('description'));
		$stmt->execute();
	}
}

?>
