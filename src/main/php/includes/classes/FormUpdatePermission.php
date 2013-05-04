<?php

use \libAllure\Form;
use \libAllure\ElementInput;
use \libAllure\DatabaseFactory;
use \libAllure\Sanitizer;

class FormUpdatePermission extends Form {
	public function __construct() {
		parent::__construct('formUpdatePermission', 'Update permission');

		$permission = $this->getPermission();

		$this->addElementHidden('id', $permission['key']);
		$this->addElement(new ElementInput('description', 'Description', $permission['description']));

		$this->addDefaultButtons();
	}

	private function getPermission() {
		$sql = 'SELECT p.key, p.description FROM permissions p WHERE p.key = :key';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);

		$stmt->bindValue(':key', Sanitizer::getInstance()->filterString('id'));
		$stmt->execute();

		return $stmt->fetchRow();
	}

	public function process() {
		$sql = 'UPDATE permissions SET description = :description WHERE `key` = :key';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':key', $this->getElementValue('id'));
		$stmt->bindValue(':description', $this->getElementValue('description'));
		$stmt->execute();
	}
}

?>
