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

		$this->hasEvents = false;

		$this->ticketCosts = $this->getTicketCosts();

		foreach ($events as $event) {
			$this->hasEvents = true;

			$this->eventsSel->addOption($event['name'] . ' - ' . doubleToGbp($this->ticketCosts[$event['id']]), $event['id']);
		}

		$this->addElement(new ElementHtml('desc', null, 'If you cannot see the event you want in the list, you need to sign up to it first!'));

		$this->addElement($this->eventsSel);
		$this->addElement(new ElementHidden('action', null, 'add'));
		$this->addDefaultButtons('Add ticket for myself to basket');
	}

	private function getTicketCosts() {
		$sql = 'SELECT s.event, s.ticketCost FROM signups s WHERE s.user = :userId';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':userId', Session::getUser()->getId());
		$stmt->execute();

		$costs = array();

		foreach ($stmt->fetchAll() as $signup) {
			$costs[$signup['event']] = $signup['ticketCost'];
		}

		return $costs;
	}

	public function process() {
		$event = Events::getById($this->getElementvalue('event'));

		Basket::addEvent($event, $this->ticketCosts[$event['id']]);
	}
}

?>
