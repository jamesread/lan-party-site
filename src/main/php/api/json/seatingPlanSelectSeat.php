<?php

set_include_path(get_include_path() . PATH_SEPARATOR . '../../');
require_once 'includes/common.php';
require_once 'includes/functions.seatingPlan.php';

use \libAllure\Sanitizer;
use \libAllure\DatabaseFactory;
use \libAllure\Session;

$seat = Sanitizer::getInstance()->filterUint('seat');
$event = Sanitizer::getInstance()->filterUint('event');
$event = Events::getById($event);

function getSeatForUser($eventId, $userId = null) {
	if (empty($userId)) {
		$userId = Session::getUser()->getId();
	}

	$sql = 'SELECT s.seat FROM seatingplan_seat_selections s WHERE s.event = :event AND s.user = :user';
	$stmt = DatabaseFactory::getInstance()->prepare($sql);
	$stmt->bindValue(':event', $eventId);
	$stmt->bindValue(':user', $userId);
	$stmt->execute();

	return $stmt->fetchAll();
}

function deleteSeatsForUser($eventId, $userId = null) {
	if (empty($userId)) {
		$userId = Session::getUser()->getId();
	}

	$sql = 'DELETE FROM seatingplan_seat_selections WHERE event = :event AND user = :user ';
	$stmt = DatabaseFactory::getInstance()->prepare($sql);
	$stmt->bindValue(':event', $eventId);
	$stmt->bindValue(':user', $userId);
	$stmt->execute();
}

function setUserInSeat($eventId, $seatId, $userId = null) {
	if (empty($userId)) {
		$userId = Session::getUser()->getId();
	}

	logActivity(Session::getUser()->getUsername() . ' selected seat ' . $seatId . ' for event ' . $eventId);

	$sql = 'INSERT INTO seatingplan_seat_selections (seat, event, user) VALUES (:seat, :event, :user1) ON DUPLICATE KEY UPDATE user = :user2';
	$stmt = DatabaseFactory::getInstance()->prepare($sql);
	$stmt->bindValue(':seat', $seatId);
	$stmt->bindValue(':event', $eventId);
	$stmt->bindValue(':user1', $userId);
	$stmt->bindValue(':user2', $userId);
	$stmt->execute();
}

function jsonError($errorMessage) {
	echo json_encode(array(
		'type' => 'error',
		'message' => $errorMessage,
	)); 

	exit;
}

function jsonSuccess($message, array $seatChanges) {
	echo json_encode(array(
		'type' => 'success',
		'message' => $message,
		'seatChanges' => $seatChanges,
	));

	exit;
}

if (!Session::isLoggedIn()) {
	jsonError('You are not logged in!');
}

$status = getSignupStatus(Session::getUser()->getId(), $event['id']);

if ($status != 'PAID' && $status != 'CONFIRMED' && $status != 'PAYPAL_WAITING' && $status != 'STAFF') {
	jsonError("You haven't paid for a ticket!");
}

if (getUserInSeat($event['id'], $seat)) {
	jsonError("That seat is already occupied!");
}

$seatChanges = array();

$currentSeats = getSeatForUser($event['id']);

foreach ($currentSeats as $itemCurrentSeat) {
	$seatChanges[] = getJsonSeatChange('delete', $itemCurrentSeat['seat'], Session::getUser()->getUsername());
}

deleteSeatsForUser($event['id']);
setUserInSeat($event['id'], $seat);

$seatChanges[] = getJsonSeatChange('set', $seat, Session::getUser()->getUsername());

jsonSuccess('Seat selected!', $seatChanges);

?>


