<?php

require_once 'includes/classes/Group.php';

use \libAllure\Form;
use \libAllure\ElementInput;
use \libAllure\ElementHidden;
use \libAllure\Sanitizer;

class FormGroupEdit extends Form {
	public function __construct() {
		parent::__construct('formGroupEdit', 'Edit Group');

		$id = Sanitizer::getInstance()->filterUint('id');
		$group = new Group($id);

		$this->addElement(new ElementHidden('id', null, $group->getId()));
		$this->addElement(new ElementInput('title', 'Title', $group->getTitle()));
		$this->addElement(new ElementInput('css', 'CSS', $group->getAttribute('css'), 'Additional styles to be applied to this group title (eg: color: red) '));
		$this->getElement('css')->setMinMaxLengths(0, 128);
		$this->addDefaultButtons();
	}

	public function process() {
		global $db;

		$sql = 'UPDATE `groups` SET title = :title, css = :css WHERE id = :id ';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':title', $this->getElementValue('title'));
		$stmt->bindValue(':css', $this->getElementValue('css'));
		$stmt->bindValue(':id', $this->getElementValue('id'));
		$stmt->execute();

		redirect('group.php?action=view&amp;id=' . $this->getElementValue('id'), 'Group edited.');
	}
}

?>
