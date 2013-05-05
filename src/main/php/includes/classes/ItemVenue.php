<?php

require_once 'includes/classes/DatabaseItem.php';

use \libAllure\DatabaseFactory;

class ItemVenue extends DatabaseItem {
	public function __construct($id) {
		parent::__construct($id, 'venues');
	}

	public function getEvents() {
		$sql = 'SELECT e.id, e.name FROM events e WHERE e.venue = :vid ORDER BY e.date ASC';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':vid', $this->getId());
		$stmt->execute();

		return $stmt->fetchAll();
	}
}

?>
