<?php

require_once '../../includes/common.php';

use \libAllure\Sanitizer;
use \libAllure\ErrorHandler;
use \libAllure\DatabaseFactory;
use \libAllure\Session;
use \libAllure\User;

ErrorHandler::getInstance()->beGreedy();

function apiReturn($status, $message = null) {
	echo "$status\n";

	if (isset($_REQUEST['debug'])) {
		echo $message;
	}

	exit;
}

function getEvent() {
	$sql = 'SELECT e.* FROM events e WHERE date_add(date, interval duration hour) > now() ORDER BY e.date ASC LIMIT 1';
	$stmt = DatabaseFactory::getInstance()->prepare($sql);
	$stmt->execute();

	$event = $stmt->fetchRowNotNull();

	return $event;
}

function getAuthenticatedMachines($user, $event) {
	$sql = 'SELECT a.id FROM authenticated_machines a WHERE a.user = :user AND a.event = :event';
	$stmt = DatabaseFactory::getInstance()->prepare($sql);
	$stmt->bindValue(':user', $user);
	$stmt->bindValue(':event', $event);
	$stmt->execute();

	$authenticatedMachines = $stmt->fetchAll();

	return $authenticatedMachines;
}

$sanitizer = Sanitizer::getInstance();

$username = $sanitizer->filterString('username');
$password = $sanitizer->filterString('password');
$isStaff = $sanitizer->filterString('fullrequest');

try {
	Session::checkCredentials($username, $password);
	$user = User::getUser($username);
} catch (\libAllure\UserNotFoundException $e) {
	apiReturn('reject-authentication', 'User not found');
} catch (\libAllure\IncorrectPasswordException $e) {
	apiReturn('reject-authentication', 'Password is incorrect');
}

$event = getEvent();
$signupStatus = getSignupStatus($user->getId(), $event['id']);

switch ($signupStatus) {
	case 'PAID':
		$authenticatedMachines = getAuthenticatedMachines($user->getId(), $event['id']);

		$sql = 'SELECT s.numberMachinesAllowed FROM signups s WHERE s.user = :user AND s.event = :event';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':user', $user->getId());
		$stmt->bindValue(':event', $event['id']);		
		$stmt->execute();

		$signup = $stmt->fetchRowNotNull();

		if (count($authenticatedMachines) >= $signup['numberMachinesAllowed']) {
			apiReturn('reject-overuse');
		} else {
			$sql = 'INSERT INTO authenticated_machines (user, event, seat, ip, hostname, mac) VALUES (:user, :event, :seat, :ip, :hostname, :mac)';
			$stmt = DatabaseFactory::getInstance()->prepare($sql);
			$stmt->bindValue(':user', $user->getId());
			$stmt->bindValue(':event', $event['id']);
			$stmt->bindValue(':seat', $sanitizer->filterString('seat'));
			$stmt->bindValue(':ip', $sanitizer->filterString('ip'));
			$stmt->bindValue(':hostname', $sanitizer->filterString('hostname'));
			$stmt->bindValue(':mac', $sanitizer->filterString('mac'));
			$stmt->execute();

			Events::setSignupStatus($user->getId(), $event['id'], 'ATTENDED');
			Events::appendSignupComment($user->getId(), $event['id'], 'Authenticated machine: ' . $sanitizer->filterString('mac'));

			apiReturn('allow');
		}
	case 'STAFF':
		apiReturn('allow-full');
	case 'SIGNEDUP':
		apiReturn('reject-payment');
	default: 
		apiReturn('fatal', 'Unrecognised signup status: ' . $signupStatus);
}

?>
