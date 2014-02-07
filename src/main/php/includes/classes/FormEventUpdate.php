<?php

require_once 'includes/classes/Events.php';

use \libAllure\Form;
use \libAllure\ElementHidden;
use \libAllure\ElementInput;
use \libAllure\ElementNumeric;
use \libAllure\ElementDate;
use \libAllure\ElementSelect;
use \libAllure\DatabaseFactory;
use \libAllure\ElementCheckbox;

class FormEventUpdate extends Form {
	public function __construct($eventId) {
		parent::__construct('eventUpdate', 'Event update');

		$event = Events::getById($eventId);

		$this->addElement(new ElementHidden('action', null, 'update'));
		$this->addElement(new ElementHidden('id', null, $event['id']));

		$this->addSection('Basics');
		$this->addElement(new ElementInput('name', 'Event name', $event['name']));
		$this->addElement($this->getElementGalleries($event['gallery']));
		$this->addElement(new ElementNumeric('totalSeats', 'Total seats', $event['totalSeats']));
		$this->addElement(new ElementInput('comment', 'Comment', $event['comment']));
		$this->addElement(new ElementCheckbox('published', 'Published', $event['published']));

		$this->addSection('When and where?');
		$this->addElement($this->getElementVenues($event['venueId']));
		$this->addElement(new ElementDate('dateStart', 'Start', formatDt($event['start'])));
		$this->addElement(new ElementNumeric('duration', 'Duration', $event['duration']));

		$this->addSection('Tickets');
		$this->addElement($this->getElementSeatingplan($event['seatingPlan']));
		$this->addElement(new ElementNumeric('priceInAdv', 'Price in advance', $event['priceInAdv']));
		$this->addElement(new ElementNumeric('priceOnDoor', 'Price on door', $event['priceOnDoor']));
		$this->addElement($this->getElementSignups($event['signups']));

		$this->requireFields(array('name', 'totalSeats'));

		$this->addButtons(Form::BTN_SUBMIT);
	}

	private function getElementSeatingPlan($seatingPlan) {
		$el = new ElementSelect('seatingPlan', 'Seating Plan');
		$el->addOption('(none)', null);

		$sql = 'SELECT sp.id, sp.name FROM seatingplans sp ORDER BY sp.name ASC';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->execute();

		foreach ($stmt->fetchAll() as $itemSeatingPlan) {
			$el->addOption($itemSeatingPlan['name'], $itemSeatingPlan['id']);
		}
		
		$el->setValue($seatingPlan);

		return $el;
	}

	private function getElementGalleries($id) {
		$el = $this->addElement(new ElementSelect('gallery', 'Gallery ID', $id));
		$el->addOption('(none)', null);

		$sql = 'SELECT g.id, g.title FROM galleries g';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->execute();

		foreach ($stmt->fetchAll() as $itemGallery) {
			$el->addOption($itemGallery['title'], $itemGallery['id']);
		}	

		return $el; 
	}

	private function getElementVenues($id) {
		$el = $this->addElement(new ElementSelect('venue', 'Venue', $id));
		
		$sql = 'SELECT v.id, v.name FROM venues v';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->execute();

		foreach ($stmt->fetchAll() as $itemVenue) {
			$el->addOption($itemVenue['name'], $itemVenue['id']);
		}

		return $el;
	}

	private function getElementSignups($status) {
		$el = new ElementSelect('signups', 'Signups', $status);
		$el->addOption('off', 'off - nobody can signup');
		$el->addOption('punters', 'punters - anyone can signup and add tickets to the basket');
		$el->addOption('staff', 'staff - only staff can sign up', 'staff');
		$el->setValue($status);

		return $el;
	}

	protected function validateExtended() {
		$this->validateTotalSeats();
	}

	private function validateTotalSeats() {
		$v = $this->getElementValue('totalSeats');

		if (!ctype_digit($v)) {
			$this->setElementError('totalSeats', 'This must be a numer!');
			return;
		}

		if ($v < 0) {
			$this->setElementError('totalSeats', 'This must be at least 1.');
			return;
		}

		if ($v > 1000) {
			$this->setElementError('totalSeats', 'Too many seats, please make it between 1 and 1000.');
			break;
		}
	}

	public function process() {
		global $db;

		$sql = 'UPDATE events SET seatingPlan = :seatingPlan, name = :name, gallery = :gallery, venue = :venue, priceInAdv = :priceInAdv, priceOndoor = :priceOnDoor, total_seats = :totalSeats, signups = :signups, date = :start, duration = :duration, comment = :comment, published = :published WHERE id = :id ';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':seatingPlan', $this->getElementValue('seatingPlan'));
		$stmt->bindValue(':name', $this->getElementValue('name'));
		$stmt->bindValue(':gallery', $this->getElementValue('gallery'));
		$stmt->bindValue(':venue', $this->getElementValue('venue'));
		$stmt->bindValue(':priceInAdv', $this->getElementValue('priceInAdv'));
		$stmt->bindValue(':priceOnDoor', $this->getElementValue('priceOnDoor'));
 		$stmt->bindValue(':totalSeats', $this->getElementValue('totalSeats'));
		$stmt->bindValue(':signups', $this->getElementValue('signups'));
		$stmt->bindValue(':start', $this->getElementValue('dateStart'));
		$stmt->bindValue(':duration', $this->getElementValue('duration'));
		$stmt->bindValue(':comment', $this->getElementValue('comment'));
		$stmt->bindValue(':published', $this->getElementValue('published'));
 		$stmt->bindValue(':id', $this->getElementValue('id'));
 		$stmt->execute();
	}
}

?>
