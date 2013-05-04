<?php

use \libAllure\Form;
use \libAllure\ElementAlphaNumeric;

class FormGroupCreate extends Form {
	public function __construct() {
		parent::__construct('groupCreate', 'Create group');

		$this->addElement(new ElementAlphaNumeric('title', 'Title'));
		$this->addDefaultButtons();
	}

	public function validateExtended() {
		global $db;

		$sql = 'SELECT title FROM groups WHERE title = :title';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':title', $this->getElementValue('title'));
		$stmt->execute();

		if ($stmt->numRows() > 0) {
			$this->setElementError('title', 'A group with that name already exists.');
		}
	}

	public function process() {
		global $db;
		$sql = 'INSERT INTO groups (title) VALUES (:title) ';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':title', $this->getElementValue('title'));
		$stmt->execute();

		redirect('group.php?action=view&amp;id=' . $db->lastInsertId(), 'Group created.');
	}
}

?>
