<?php

use \libAllure\Form;
use \libAllure\ElementHidden;
use \libAllure\ElementInput;
use \libAllure\ElementNumeric;
use \libAllure\ElementCheckbox;
use \libAllure\Database;

class FormSurveyEdit extends Form {
	public function __construct(array $survey) {
		parent::__construct('editSurvey', 'Edit survey');

		$this->addElement(new ElementHidden('id', null, $survey['id']));
		$this->addElement(new ElementHidden('action', null, 'edit'));
		$this->addElement(new ElementInput('title', 'Title', $survey['title']));
		$this->addElement(new ElementNumeric('count', 'Vote count', $survey['count'], 'How many options may voters choose?'));
		$this->addElement(new ElementCheckbox('active', 'Active', $survey['active'], 'Is the survey active?'));

		$this->addDefaultButtons();
	}

	public function process() {
		global $db;

		$sql = 'UPDATE surveys SET title = :title, count = :count, active = :active WHERE id = :id';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':title', $this->getElementValue('title'), Database::PARAM_STR);
		$stmt->bindValue(':count', $this->getElementValue('count'), Database::PARAM_INT);
		$stmt->bindValue(':active', $this->getElementValue('active'), Database::PARAM_INT);
		$stmt->bindValue(':id', $this->getElementValue('id'), Database::PARAM_INT);
		$stmt->execute();
	}
}

?>
