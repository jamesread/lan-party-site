<?php

use \libAllure\Form;
use \libAllure\ElementHidden;
use \libAllure\ElementTextbox;
use \libAllure\ElementCheckbox;
use \libAllure\ElementNumeric;
use \libAllure\Sanitizer;
use \libAllure\ElementSelect;
use \libAllure\ElementHtml;
use \libAllure\Session;
use \libAllure\ElementButton;

class FormSignupEdit extends Form {
	public function __construct() {
		parent::__construct('signupEdit', 'Update signup');

		$this->signup = $this->getSignup();
		$this->setTitle('Update signup for: ' . $this->signup['username']);

		$elStatus = new ElementSelect('status', 'Status');

		$this->addElementReadOnly('User ID', $this->signup['userId']);
		$this->addElement(new ElementHidden('id', null, $this->signup['id']));
		$this->addElement($this->getStatusElement($this->signup['status']));
		$this->addElement(new ElementTextbox('comments', 'Comments', ''));
		$this->addElement(new ElementHtml('previousComments', 'Previous Comments', nl2br($this->signup['comments'])));
		$this->addElement(new ElementCheckbox('gigabit', 'Gigabit', $this->signup['gigabit']));
		$this->addElement(new ElementNumeric('ticketCost', 'Ticket cost', $this->signup['ticketCost']));
		$this->addElement(new ElementNumeric('numberMachinesAllowed', 'Machines Allowed', $this->signup['numberMachinesAllowed']));

		$this->requireFields('status', 'comments');

		$this->addDefaultButtons();
	}

	private function getStatusElement($currentValue) {
		$el = new ElementSelect('status', 'Status', $currentValue);
		$el->addOption('ATTENDED');
		$el->addOption('CANCELLED');
		$el->addOption('SIGNEDUP');
		$el->addOption('STAFF');
		$el->addOption('CASH_IN_POST');
		$el->addOption('BACS_WAITING');
		$el->addOption('PAID');
		$el->addOption('DELETE');

		return $el;
	}

	private function getSignup() {
		if (!isset($this->signup)) {
			global $db;

			$sql = 'SELECT s.*, u.username, u.id AS userId, e.name AS eventTitle FROM signups s LEFT JOIN users u ON s.user = u.id LEFT JOIN events e ON s.event = e.id WHERE s.id = :id LIMIT 1';
			$stmt = $db->prepare($sql);
			$stmt->bindValue('id', Sanitizer::getInstance()->filterUint('id'));
			$stmt->execute();

			if ($stmt->numRows() == 0) {
				throw new Exception('Signup not found.');
			}

			$this->signup = $stmt->fetchRow();
		}

		return $this->signup;
	}

	private function getChangeMetadata() {
		$changelog = ' ';
		$changelog .= $this->getChangedMetadataValue('ticketCost', 'Ticket cost');
		$changelog .= $this->getChangedMetadataValue('gigabit', 'Gigabit NIC');
		$changelog .= $this->getChangedMetadataValue('status', 'Status');

		return $changelog;
	}

	private function getChangedMetadataValue($field, $title) {
		if ($this->signup[$field] != $this->getElementValue($field)) {
			return $title . ' changed from ' . $this->signup[$field] . ' to ' . $this->getElementValue($field) . '. ';
		} else {
			return null;
		}
	}

	public function process() {
		$this->processUpdate();
	}

	protected function processDelete() {
		global $db;

		$sql = 'DELETE FROM signups WHERE id = :signupId LIMIT 1';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':signupId', $this->getElementValue('id'));
		$stmt->execute();

		logActivity('Signup DELETED for ' . $this->signup['username'] . ' to event ' . $this->signup['eventTitle'] . '. ' . $this->getChangeMetadata());

		redirect('viewEvent.php?id=' . $this->signup['event'], 'Signup deleted, oh dear.');
	}

	protected function processUpdate() {
		global $db;

		if ($this->getElementValue('status') == "DELETE") {
			$this->processDelete();
		}

		$sanitizer = Sanitizer::getInstance();

		$sql = 'UPDATE signups SET status = :status, numberMachinesAllowed = :machinesAllowed, comments = concat(comments, "\n", now(), " (", :staffUsername, ") - ", :comments, :changeMetadata), gigabit = :gigabit, ticketCost = :ticketCost WHERE id = :id';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':id', $this->getElementValue('id'));
		$stmt->bindValue(':status', $this->getElementValue('status'));
		$stmt->bindValue(':comments', $sanitizer->formatString($this->getElementValue('comments')));
		$stmt->bindValue(':gigabit', $sanitizer->formatBool($this->getElementValue('gigabit')));
		$stmt->bindValue(':ticketCost', $this->getElementValue('ticketCost'));
		$stmt->bindValue(':staffUsername', Session::getUser()->getUsername());
		$stmt->bindValue(':changeMetadata', $this->getChangeMetadata());
		$stmt->bindValue(':machinesAllowed', $this->getElementValue('numberMachinesAllowed'));
		$stmt->execute();

		$this->signup = $this->getSignup();

		$sql = 'SELECT e.id FROM events e WHERE e.id = :eventId LIMIT 1';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':eventId', $this->signup['event']);
		$stmt->execute();

		logActivity('Signup updated for ' . $this->signup['username'] . ' to event ' . $this->signup['eventTitle'] . '. ' . $this->getElementValue('comments') . '. ' . $this->getChangeMetadata());

		redirect('viewEvent.php?id=' . $this->signup['event'], 'Signup edited.');
	}
}

?>
