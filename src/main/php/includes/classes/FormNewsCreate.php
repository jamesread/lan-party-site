<?php

use \libAllure\Form;
use \libAllure\ElementHidden;
use \libAllure\ElementInput;
use \libAllure\ElementTextbox;
use \libAllure\Session;

class FormNewsCreate extends Form {
	public function __construct() {
		parent::__construct('addNews', 'Add news');

		$this->addElement(new ElementHidden('action', null, 'add'));
		$this->addElement(new ElementInput('title', 'Title'));
		$this->addElement(new ElementTextbox('content', 'Content'));

		$this->addDefaultButtons();
	}

	public function process() {
		global $db;

		$sql = 'INSERT INTO news (title, content, author, date) VALUES (:title, :content, :author, now())';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':title', $this->getElementValue('title'));
		$stmt->bindValue(':content', $this->getElementValue('content'));
		$stmt->bindValue(':author', Session::getUser()->getId());
		$stmt->execute();
	}
}

?>
