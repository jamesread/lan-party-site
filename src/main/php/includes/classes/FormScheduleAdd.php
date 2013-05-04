<?php

use \libAllure\Form;
use \libAllure\ElementHidden;
use \libAllure\ElementDate;
use \libAllure\ElementInputRegex;
use \libAllure\ElementInput;
use \libAllure\ElementSelect;

class FormScheduleAdd extends Form {
	private $eventId;

	public function __construct($eventId) {
		parent::__construct('formScheduleAdd', 'Add to schedule');

		$this->eventId = $eventId;

		$this->addElement(new ElementHidden('id', null, $eventId));
		$this->addElement(new ElementDate('start', 'Start date'));
		$this->addElement(new ElementInputRegex('startTime', 'Start time'));
		$this->getElement('startTime')->setPatternToTime();
		$this->addElement(new ElementInput('message', 'Description'));

		$this->addElement($this->getIconElement());
		$this->requireFields(array('start', 'message'));

		$this->addDefaultButtons();
	}

	public function getIconElement() {
		$el = new ElementSelect('icon', 'Icon');

		$contents = scandir('resources/images/icons/games/');

		foreach ($contents as $icon) {
			if ($icon[0] == '.') {
				continue;
			}

			$el->addOption($icon);
		}

		return $el;
	}

	public function process() {
		global $db;

		$sql = 'INSERT INTO event_schedule (start, message, event, icon) VALUES (:start, :message, :event, :icon)';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':start', $this->getElementValue('start') . ' ' . $this->getElementValue('startTime'));
		$stmt->bindValue(':message', $this->getElementValue('message'));
		$stmt->bindValue(':event', $this->eventId);
		$stmt->bindValue(':icon', $this->getElementValue('icon'));
		$stmt->execute();
	}
}

?>
