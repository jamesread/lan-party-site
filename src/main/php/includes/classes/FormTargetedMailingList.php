<?php

require_once 'includes/classes/Events.php';

use \libAllure\Form;
use \libAllure\ElementCheckbox;
use \libAllure\ElementSelect;

class FormTargetedMailingList extends Form {
	public function __construct() {
		parent::__construct('targetedMailingList', 'Tageted Mailing List');

		$this->addElement($this->getEventListElement());
		$this->addElement(new ElementCheckbox('ignoreOptOut', 'Include users preferences', 1, 'When checked, the mailing list will even contain users who chose to opt out of the main mailing list.'));

		$this->addButtons(Form::BTN_SUBMIT);
	}

	private function getEventListElement() {
		$el = new ElementSelect('eventList', 'Event');

		foreach (Events::getAllUpcommingEvents() as $event) {
			$el->addOption($event['name'], $event['id']);
		}

		return $el;
	}

	public function process() {
		redirect('mailingList.php?eventList=' . $this->getElementValue('eventList'), 'Here you go...');
	}
}

?>
