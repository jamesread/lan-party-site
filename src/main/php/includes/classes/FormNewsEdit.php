<?php

use \libAllure\Form;
use \libAllure\ElementHidden;
use \libAllure\ElementInput;
use \libAllure\ElementTextbox;

class FormNewsEdit extends Form {
	public function __construct($id) {
		parent::__construct('edit', 'Edit news');

		global $db;

		$sql = 'SELECT title, content FROM news WHERE id = :id LIMIT 1';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':id', $id);
		$stmt->execute();

		if ($stmt->numRows() == 0) {
			throw new Exception('News item not found');
		}

		$news = $stmt->fetchRow();

		$this->addElement(new ElementHidden('action', null, 'edit'));
		$this->addElement(new ElementHidden('id', null, $id));
		$this->addElement(new ElementInput('title', 'Title', $news['title']));
		$this->addElement(new ElementTextbox('content', 'Content', stripslashes(htmlify($news['content']))));

		$this->addDefaultButtons();
	}

	public function process() {
		global $db;

		$sql = 'UPDATE news SET title = :title, content = :content WHERE id = :id';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':title', $this->getElementValue('title'));
		$stmt->bindValue(':content', $this->getElementValue('content'));
		$stmt->bindValue(':id', $this->getElementValue('id'));
		$stmt->execute();
	}
}

?>
