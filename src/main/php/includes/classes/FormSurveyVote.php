<?php

use \libAllure\Form;
use \libAllure\ElementHtml;
use \libAllure\ElementHidden;
use \libAllure\ElementMultiCheck;
use \libAllure\Session;

class FormSurveyVote extends Form {
	private $survey;

	public function __construct(array $survey, array $options) {
		parent::__construct('formVote', 'Vote on survey');

		$this->survey = $survey;
		$this->addElement(new ElementHtml(null, 'desc', null, 'You may cast ' . $survey['count'] . ' vote(s), if you cast more votes the first valid options will be taken.'));

		$elVote = new ElementMultiCheck('voteValue', 'Vote');

		foreach ($options as $option) {
			$elVote->addOption($option['id'], $option['value']);
		}

		$this->addElement($elVote);
		$this->addElement(new ElementHidden('id', null, $survey['id']));
		$this->addElement(new ElementHidden('action', null, 'view'));

		$this->addButtons(Form::BTN_SUBMIT);
	}

	public function process() {
		global $db;

		$db->beginTransaction();

		$sql = 'DELETE FROM survey_votes WHERE opt IN (SELECT id FROM survey_options WHERE survey = :survey) AND user = :user ';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':survey', $this->survey['id']);
		$stmt->bindValue(':user', Session::getUser()->getId());
		$stmt->execute();

		$sql = 'INSERT INTO survey_votes (user, opt) VALUES (:user, :option)';
		$stmt = $db->prepare($sql);

		$count = 0;
		foreach ($this->getElementValue('voteValue') as $vote) {
			if ($count >= $this->survey['count']) {
				break;
			} else {
				$count++;
			}

			$stmt->bindValue(':user', Session::getUser()->getId());
			$stmt->bindValue(':option', $vote);
			$stmt->execute();
		}

		$db->commit();
	}
}

?>
