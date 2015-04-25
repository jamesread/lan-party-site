<?php

use \libAllure\Session;
use \libAllure\DatabaseFactory;

function getJsonSeatChange($type, $seatId, $username = null, $usercss = null, $seatCss = null) {
	if (Session::isLoggedIn() && $username == Session::getUser()->getUsername()) {
		$username = 'self';
	}
	
	return array(
		'type' => $type,
		'seat' => $seatId,
		'username' => $username,
		'usernameCss' => $usercss,
		'seatCss' => $seatCss,
	);
}

function getSeats($eventId) {
	$sql = 'SELECT s.seat, u.username, g.css AS usernameCss, g.seatingPlanCss AS seatCss FROM seatingplan_seat_selections s JOIN users u ON s.user = u.id LEFT JOIN groups g ON u.group = g.id WHERE s.event = :event';
	$stmt = DatabaseFactory::getInstance()->prepare($sql);
	$stmt->bindValue(':event', $eventId);
	$stmt->execute();

	return $stmt->fetchAll();
}

function getUserInSeat($eventId, $seatId) {
	$sql = 'SELECT s.seat, s.user, u.username FROM seatingplan_seat_selections s JOIN users u ON s.user = u.id WHERE s.event = :event AND s.seat = :seat LIMIT 1';
	$stmt = DatabaseFactory::getInstance()->prepare($sql);
	$stmt->bindValue(':event', $eventId);
	$stmt->bindValue(':seat', $seatId);
	$stmt->execute();

	$seatSelection = $stmt->fetchRow();

	if (!empty($seatSelection)) {
		return $seatSelection['username'];
	} else {
		return false;
	}
}

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

function setSeatForUser($eventId, $userId, $seatId) {
	if (getUserInSeat($eventId, $seatId)) {
		return; // dont overwrite
	}

	// if getSeatForUser != null, insert

	$sql = 'UPDATE seatingplan_seat_selections s SET seat = :seat WHERE event = :event and USER = :user';
	$stmt = DatabaseFactory::getInstance()->prepare($sql);
	$stmt->bindValue(':seat', $seatId);
	$stmt->bindValue(':event', $eventId);
	$stmt->bindValue(':user', $userId);
	$stmt->execute();
}

function removeSeat($eventId, $userId, $checkCancelled = true) {
	if ($checkCancelled) {
		$signupStatus = getSignupStatus($userId, $eventId);

		if ($signupStatus != 'CANCELLED') {
			throw new Exception('Cannot remove seat from a user, as they have not cancelled.');
		}
	}

	$sql = 'DELETE FROM seatingplan_seat_selections WHERE event = :event AND user = :user';
	$stmt = DatabaseFactory::getInstance()->prepare($sql);
	$stmt->bindValue(':event', $eventId);
	$stmt->bindValue(':user', $userId);
	$stmt->execute();

	logActivity('Removed seat for _u_ at _e_', null, array(
		'user' => $userId,
		'event' => $eventId
	));
}

function setUserInSeat($eventId, $seatId, $userId = null) {
	if (empty($userId)) {
		$userId = Session::getUser()->getId();
	}

	logActivity('_u_' . ' selected seat ' . $seatId . ' for event _e_', null, array(
		'user' => $userId,
		'event' => $eventId
	));

	$sql = 'INSERT INTO seatingplan_seat_selections (seat, event, user) VALUES (:seat, :event, :user1) ON DUPLICATE KEY UPDATE user = :user2';
	$stmt = DatabaseFactory::getInstance()->prepare($sql);
	$stmt->bindValue(':seat', $seatId);
	$stmt->bindValue(':event', $eventId);
	$stmt->bindValue(':user1', $userId);
	$stmt->bindValue(':user2', $userId);
	$stmt->execute();
}


function swapUsersSeats($eventId, $userId1, $userId2) {
	$seat1 = getSeatForUser($eventId, $userId1);
	$seat2 = getSeatForUser($eventId, $userId2);

	if (empty($seat1) || empty($seat2)) {
		return;
	}

//	var_dump($seat1[0]['seat'], $seat2[0]['seat'], $userId1, $userId2); exit;

	removeSeat($eventId, $userId1, false);
	removeSeat($eventId, $userId2, false);

	setUserInSeat($eventId, $seat2[0]['seat'], $userId1);
	setUserInSeat($eventId, $seat1[0]['seat'], $userId2);
}


?>
