<?php

use \libAllure\Form;
use \libAllure\ElementHidden;
use \libAllure\ElementTextbox;
use \libAllure\ElementInput;
use \libAllure\Session;

class FormContentEdit extends Form {
	private $editId;

	public function __construct($editId = false) {
		parent::__construct('contentEdit', '<a href = "listContent.php">Content edit</a> &raquo; Content Edit');

		global $db;

		$this->editId = $editId;

		if ($editId) {
			$this->addElement(new ElementHidden('action', null, 'edit'));
			$this->addElement(new ElementHidden('id', null, $this->editId));

			$sql = 'SELECT id, page, content FROM page_content WHERE id = :id';
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':id', $this->getElementValue('id'));
			$stmt->execute();
			$page = $stmt->fetchRow();
			$page['content'] = stripslashes($page['content']);
			$page['content'] = htmlentities($page['content']);

			$this->addElement(new ElementTextbox('content', 'Content', $page['content']));
			$this->addElementHidden('title', $page['page']);
		} else {
			$this->addElement(new ElementHidden('action', null, 'new'));

			$this->addElement(new ElementInput('title', 'Page Title'));
			$this->getElement('title')->setMinMaxLengths(2, 64);

			$this->addElement(new ElementTextbox('content', 'Content'));
		}

		$this->addDefaultButtons();
	}

	public function process() {
		($this->editId) ? $this->processEdit() : $this->processNew();
	}

	public function processEdit() {
		global $db;

		$sql = 'UPDATE page_content SET content = :content, updated = now(), updatedBy = :userId WHERE id = :id ';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':content', $this->getElementValue('content'));
		$stmt->bindValue(':id', $this->getElementValue('id'));
		$stmt->bindValue(':userId', Session::getUser()->getId());
		$stmt->execute();

		logActivity('Updated content: ' . $this->getElementValue('title'));

		return true;
	}

	public function processNew() {
		global $db;

		$sql = 'INSERT INTO page_content (page, content, updatedBy) VALUES (:title, :content, :userId) ';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':title', $this->getElementValue('title'));
		$stmt->bindValue(':content', $this->getElementValue('content'));
		$stmt->bindValue(':userId', Session::getUser()->getId());
		$stmt->execute();

		logActivity('Content created: ' . $this->getElementValue('title'));

		return true;
	}
}

?>
