<?php

use \libAllure\DatabaseFactory;
use \libAllure\Session;

class Basket {
	public static function getContents() {
		$sql = 'SELECT e.name AS title, u.username, e.priceInAdv AS cost, u.id AS userId, u.username, e.id AS eventId FROM basket_items bi JOIN events e ON bi.event = e.id JOIN users u ON bi.user = u.id WHERE bi.basketOwner = :userId ORDER BY e.id';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':userId', Session::getUser()->getId());
		$stmt->execute();

		return $stmt->fetchAll();
	}

	public static function clear() {
		$sql = 'DELETE FROM basket_items WHERE basketOwner = :userId ';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':userId', Session::getUser()->getId());
		$stmt->execute();
	}

	public static function getTotal() {
		$sql = 'SELECT e.priceInAdv AS cost FROM basket_items bi JOIN events e ON bi.event = e.id WHERE bi.basketOwner = :userId ';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':userId', Session::getUser()->getId());
		$stmt->execute();

		$total = 0;

		foreach ($stmt->fetchAll() as $item) {
			$total += $item['cost'];
		}

		return $total;
	}

	public static function containsEventId($eventIdToSearch) {
		$sql = 'SELECT bi.id FROM basket_items bi WHERE bi.basketOwner = :ownerId AND bi.event = :eventId AND bi.user = :userId LIMIT 1';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':ownerId', Session::getUser()->getId());
		$stmt->bindValue(':eventId', $eventIdToSearch);
		$stmt->bindValue(':userId', Session::getUser()->getId());
		$stmt->execute();

		return ($stmt->numRows() > 0);
	}

	public static function addEvent(array $event, $userId = null) {
		if ($userId == null) {
			$userId = Session::getUser()->getId();
		}

		$sql = 'INSERT INTO basket_items (event, user, basketOwner) VALUES (:event, :user, :basketOwner) ';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':event', $event['id']);
		$stmt->bindValue(':user', $userId);
		$stmt->bindValue(':basketOwner', Session::getUser()->getId());
		$stmt->execute();
	}

	public static function removeEvent(array $event, $userId) {
		$sql = 'DELETE FROM basket_items WHERE event = :eventId AND user = :user AND basketOwner = :ownerId';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':eventId', $event['id']);
		$stmt->bindValue(':user', $userId);
		$stmt->bindValue(':ownerId', Session::getUser()->getId());
		$stmt->execute();
		$eventId = $event['id'];
	}

	public static function isEmpty() {
		$sql = 'SELECT bi.id FROM basket_items bi WHERE bi.basketOwner = :userId';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':userId', Session::getUser()->getId());
		$stmt->execute();

		return ($stmt->numRows() == 0);
	}
}

?>
