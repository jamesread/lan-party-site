<?php

use \libAllure\Form;
use \libAllure\ElementHidden;
use \libAllure\ElementInput;
use \libAllure\ElementDate;
use \libAllure\ElementNumeric;
use \libAllure\ElementSelect;

class FormEventCreate extends Form {
	public function __construct() {
		parent::__construct('addEvent', '<a href = "listEvents.php">Events</a> &raquo; Create Event');

		$this->addElement(new ElementHidden('action', null, 'add'));
		$this->addElement(new ElementInput('title', 'Title'));
		$this->addElement(new ElementDate('start', 'Start'));
		$this->addElement(new ElementNumeric('duration', 'Duration', null, 'How long is the event, in hours.'));
		$this->getElement('duration')->addSuggestedValue('13', 'One day event');
		$this->getElement('duration')->addSuggestedValue('72', 'Weekend event');

		$this->requireFields('title', 'start', 'duration');

		$this->addElement($this->getVenueElement());
		$this->addDefaultButtons();
	}

	private function getVenueElement() {
		global $db;

		$el = new ElementSelect('venue', 'Venue', null, 'Where is it? ');

		$sql = 'SELECT id, name FROM venues';
		$result = $db->query($sql);

		foreach ($result->fetchAll() as $venue) {
			$el->addOption($venue['name'], $venue['id']);
		}

		return $el;
	}

	public function process() {
		global $db;

		$sql = 'INSERT INTO events (name, date, duration, venue) VALUES (:title, :start, :duration, :venue)';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':title', $this->getElementValue('title'));
		$stmt->bindValue(':start', $this->getElementValue('start'));
		$stmt->bindValue(':duration', $this->getElementValue('duration'));
		$stmt->bindValue(':venue', $this->getElementValue('venue'));
		$stmt->execute();

		logActivity('Event created: ' . $db->lastInsertId() . ' / ' . $this->getElementValue('title'));
	}
}

?>
