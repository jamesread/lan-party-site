<?php

use \libAllure\Form;
use \libAllure\DatabaseFactory;
use \libAllure\Session;
use \libAllure\ElementHtml;
use \libAllure\ElementSelect;
use \libAllure\ElementHidden;
use \libAllure\ElementButton;

class FormAddToBasket extends \libAllure\Form {
	public function __construct($events) {
		parent::__construct('addToBasket', 'Add event to Basket');

		$this->eventsSel = new ElementSelect('event', 'Event');

		$events = $this->removeEventsAlreadyInBasket($events);
		$events = $this->removeEventsAlreadySignedupFor($events);

		$this->hasEvents = false;

		foreach ($events as $event) {
			$this->hasEvents = true;
			$this->eventsSel->addOption($event['name'] . ' - ' . doubleToGbp($event['priceInAdv']), $event['id']);
		}

		$this->addElement(new ElementHtml('desc', null, 'If you cannot see the event you want in the list, you need to sign up to it first!'));

		$this->addElement($this->eventsSel);
		$this->addElement(new ElementHidden('action', null, 'add'));
		$this->addDefaultButtons('Add ticket for myself to basket');
	}

	private function removeEventsAlreadyInBasket($events) {
		foreach ($events as $key => $event) {
			if (Basket::containsEventId($event['id'])) {
				unset($events[$key]);
			}
		}

		return $events;
	}

	private function removeEventsAlreadySignedupFor($events) {
		$sql = 'SELECT s.event, s.status FROM signups s WHERE s.user = :user AND s.status != "SIGNEDUP" ';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':user', Session::getUser()->getId());
		$stmt->execute();

		$eventIds = array();
		foreach ($stmt->fetchAll() as $event) {
			$eventIds[] = $event['event'];
		}

		foreach ($events as $key => $event) {
			if (in_array($event['id'], $eventIds)) {
				unset($events[$key]);
			}
		}

		return $events;
	}

	public function process() {
		$event = Events::getById($this->getElementvalue('event'));

		Basket::addEvent($event);
	}
}

?>
