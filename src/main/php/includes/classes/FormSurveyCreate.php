<?php

use \libAllure\Form;
use \libAllure\ElementHidden;
use \libAllure\ElementInput;

class FormSurveyCreate extends Form {
	public function __construct() {
		parent::__construct('createSurveyForm', 'Create survey');

		$this->addElement(new ElementHidden('action', null, 'create'));
		$this->addElement(new ElementInput('title', 'Question', null, 'What do you want the survey to ask?'));
		$this->addDefaultButtons();
	}

	public function process() {
		global $db;

		$sql = 'INSERT INTO surveys (title, active) VALUES (:title, 1)';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':title', $this->getElementValue('title'));
		$stmt->execute();
	}
}

?>
