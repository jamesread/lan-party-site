<?php

use \libAllure\Form;
use \libAllure\ElementHidden;
use \libAllure\ElementNumeric;
use \libAllure\ElementTextbox;

use \libAllure\Sanitizer;
use \libAllure\Session;
use \libAllure\DatabaseFactory;

class FormUpdateSeatingPlan extends Form {
	public function __construct() {
		parent::__construct('updateSeatingPlan', 'Update Seating Plan');

		$id = Sanitizer::getInstance()->filterUint('id');

		$sql = 'SELECT sp.id, sp.layout, sp.seatCount FROM seatingplans sp WHERE sp.id = :id';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':id', $id);
		$stmt->execute();

		$seatingPlan = $stmt->fetchRow();
		$this->addElementHidden('id', $id);
		$this->addElement(new ElementTextbox('layout', 'Layout', $seatingPlan['layout']));
		$this->getElement('layout')->classes = "codeEditor";

		$this->addElement(new ElementNumeric('seatCount', 'Seat Count', $seatingPlan['seatCount']));

		$this->addDefaultButtons();
	}

	public function process() {
		$sql = 'UPDATE seatingplans SET layout = :layout, seatCount = :seatCount WHERE id = :id';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':layout', $this->getElementValue('layout'));
		$stmt->bindValue(':seatCount', $this->getElementValue('seatCount'));
		$stmt->bindValue(':id', $this->getElementValue('id'));
		$stmt->execute();
	}
}

?>
