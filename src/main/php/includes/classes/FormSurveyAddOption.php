<?php

use \libAllure\Form;
use \libAllure\ElementHidden;
use \libAllure\ElementInput;
use \libAllure\Database;

class FormSurveyAddOption extends Form {
	public function __construct(array $survey) {
		parent::__construct('editSurveyOptions', 'Add survey options');

		$this->addElement(new ElementHidden('id', null, $survey['id']));
		$this->addElement(new ElementHidden('action', null, 'edit'));
		$this->addElement(new ElementInput('value', 'Value'));
		$this->addDefaultButtons('Add option');
	}

	public function process() {
		global $db;

		$sql = 'INSERT INTO survey_options (value, survey) VALUES (:value, :survey) ';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':value', $this->getElementValue('value'), Database::PARAM_STR);
		$stmt->bindValue(':survey', $this->getElementValue('id'), Database::PARAM_INT);
		$stmt->execute();
	}
}

?>
