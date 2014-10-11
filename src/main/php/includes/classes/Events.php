<?php

use \libAllure\Database;
use \libAllure\Session;
use \libAllure\DatabaseFactory;
use \libAllure\SimpleFatalError;

class Events {
	public static function getSignupFinances($eventId) {
		global $db;

		$sql = 'SELECT s.status, s.ticketCost FROM signups s INNER JOIN events e ON e.id = s.event WHERE e.id = :eventId';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':eventId', $eventId);
		$stmt->execute();

		$ret = array();
		$ret['paid'] = 0;
		$ret['unaccounted'] = 0;
		$ret['signedup'] = 0;

		foreach ($stmt->fetchAll() as $signup) {
			switch ($signup['status']) {
				case 'SIGNEDUP':
					$ret['signedup'] += $signup['ticketCost'];
					break;
				case 'STAFF':
				case 'PAID':
				case 'ATTENDED':
					$ret['paid'] += $signup['ticketCost'];
					break;
				default:
					$ret['unaccounted'] += $signup['ticketCost'];
			}
		}

		return $ret;
	}

	public static function nextEvent() {
		global $db;

		$sql = 'SELECT e.id, e.name, e.date, e.duration, v.name as venue, e.seatingPlan FROM events e, venues v WHERE date_add(e.date, INTERVAL 72 HOUR) > now() AND e.venue = v.id AND e.published = 1 ORDER BY date ASC LIMIT 1';
		$result = $db->query($sql);

		if ($result->numRows() == 0) {
			return null;
		} else {
			$nextEvent = $result->fetchRow();

			$nextEvent['endDate'] = date_create($nextEvent['date']);
			$nextEvent['endDate']->modify('+' . $nextEvent['duration'] . ' hours');
			$nextEvent['endDate'] = formatDt($nextEvent['endDate']);
			$nextEvent['dateIso'] = formatDtIso($nextEvent['date']);
			$nextEvent['dateUser'] = formatDtString($nextEvent['date']);
			$nextEvent['date'] = formatDtString($nextEvent['date']); // deprecated

			return $nextEvent;
		}
	}

	public static function getSimplifiedSignupStatus($status) {
		switch ($status) {
			case 'BACS_WAITING';
		}
	}

	public static function getSignupsForEvent($id, $currentUserSignupStatus = null) {
		global $db;
		
		// Get the signup info.
		$sql = sprintf('SELECT s.id, sum(s2.status = "CANCELLED") as countCancelled, sum(s2.status = "STAFF" OR s2.status = "ATTENDED") as countAttended, s.comments, s.status, s.user, u.username, u.real_name AS userRealName, s.event, s.ticketCost, g.css AS userGroupCss FROM signups s LEFT JOIN signups s2 ON s.user = s2.user LEFT JOIN users u ON s.user = u.id LEFT JOIN `groups` g ON u.group = g.id WHERE s.event = :id GROUP BY u.id ORDER BY status ASC, u.username ASC');
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':id', $id, Database::PARAM_INT);
		$stmt->execute();

		$signups = $stmt->fetchAll();

		foreach ($signups as $index => $signup) {
			if (!empty($currentUserSignupStatus)) {
				$signups[$index]['actions'] = signupLinks($id, $currentUserSignupStatus, $signup['id'], $signup['status'], $signup['user']);
			}

			if (!Session::hasPriv('VIEW_SIGNUP_MONNIES')) {
				unset($signups[$index]['ticketCost']);
			}
		}

		return $signups;
	}

	public static function isBannedFromEvents(User $u) {
		$bannedReason = $u->getData('bannedReason');

		return !empty($bannedReason);
	}

	private static function signupDelete($userId, $eventId) {
			global $db;

			$sql = 'DELETE FROM signups WHERE user = :userId AND event = :eventId';
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':userId', $userId);
			$stmt->bindValue(':eventId', $eventId);
			$stmt->execute();
	}

	public static function appendSignupComment($userId, $eventId, $comment, $username = null) {
		if (empty($username)) {
			$username = 'system';
		}

		if (empty($comment)) {
			$comment = '(no comment)';
		}

		$sql = 'SELECT s.id FROM signups s WHERE s.user = :user AND s.event = :event';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':user', $userId);
		$stmt->bindValue(':event', $eventId);
		$stmt->execute();
		$stmt->fetchRowNotNull();

		$sql = 'UPDATE signups SET comments = concat_ws(" ", comments, "\n", now(), "(", :username , ") -", :comments) WHERE user = :user AND event = :event ';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':comments', $comment);
		$stmt->bindValue(':user', $userId);
		$stmt->bindValue(':event', $eventId);
		$stmt->bindValue(':username', $username);
		$stmt->execute();
	}

	/**
	 * FIXME: Check they are actually allowed to set the status.
	 */
	public static function setSignupStatus($userId, $eventId, $status) {
		global $db;
		$status = strtoupper($status);

		if ($userId != Session::getUser()->getId() && !Session::hasPriv('SIGNUPS_MODIFY')) {
			throw new PermissionException('You may only edit your own signup.');
		}

		if ($status == 'DELETE') {
			self::signupDelete($userId, $eventId);

			return;
		}

		$sql = 'SELECT id FROM signups AS s WHERE s.user = :userId AND s.event = :eventId';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':userId', $userId);
		$stmt->bindValue(':eventId', $eventId);
		$stmt->execute();

		if ($stmt->numRows() == 0) {
			self::signupCreate($userId, $eventId, $status);
		} else {
			$signupId = $stmt->fetchRow();
			$signupId = $signupId['id'];

			self::signupUpdate($signupId, $status);
		}
	}

	private static function signupUpdate($signupId, $status) {
		if ($status == 'PAID' && !Session::hasPriv('SIGNUPS_MODIFY')) {
			throw new PermissionException();
		}

		global $db;

		$sql = 'UPDATE signups SET status = :status WHERE id = :id ';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':status', $status);
		$stmt->bindValue(':id', $signupId);
		$stmt->execute();
	}

	private static function signupCreate($userId, $eventId, $status) {
			global $db;

			$event = Events::getById($eventId);

			$sql = 'INSERT INTO signups (user, event, status, ticketCost, comments) VALUES (:user, :event, :status, :cost, concat(now(), " Signup created.")) ';
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':user', $userId);
			$stmt->bindValue(':event', $eventId);
			$stmt->bindValue(':status', $status);
			$stmt->bindValue(':cost', $event['priceInAdv']);
			$stmt->execute();
	}

	public static function getSignupableEvents() {
		global $db;

		$sql = 'SELECT id, name, priceInAdv FROM events WHERE date > curdate() AND signups = "punters" OR signups = "waitinglist" ';
		$result = $db->query($sql);

		if ($result->numRows() == 0) {
			return array();
		} else {
			return self::normalize($result->fetchAll());
		}
	}

	private static function normalize(array $events) {
		return $events;
	}

	public static function getByGalleryId($id) {
		$sql = 'SELECT e.id FROM events e WHERE e.gallery = :galleryId';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':galleryId', $id);
		$stmt->execute();

		$event = $stmt->fetchRowNotNull();

		return self::getById($event['id']);
	}

	public static function getById($id) {
		global $db;

		$id = intval($id);

		$sql = 'SELECT e.id, e.seatingPlan, e.published, e.comment, e.date, e.duration, e.total_seats as totalSeats, e.name, e.venue AS venueId, v.name AS venueName, e.priceOnDoor, e.priceInAdv, e.gallery, e.signups FROM events e, venues v WHERE e.id = :id AND e.venue = v.id LIMIT 1';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':id', $id);
		$stmt->execute();

		if ($stmt->numRows() == 0) {
			throw new SimpleFatalError('That event does not exist.');
		}

		$event = $stmt->fetchRow();

		$event['start'] = date_create($event['date']);
		$event['finish'] = clone($event['start']);
		$event['finish']->modify('+' . $event['duration'] . ' hours');
		$event['inPast'] = self::isEventDateInPast($event);

		return $event;
	}

	private static function isEventDateInPast(array $event) {
		return (strtotime(formatDtIso($event['finish'])) < time());
	}

	public static function getAllEvents($publishedOnly = true) {
		global $db;

		if ($publishedOnly) {
			$sql = 'SELECT e.id, e.name, e.date, e.duration, e.signups, v.name AS "venue", e.total_seats,priceInAdv, COUNT(s.id) AS signups FROM events AS e LEFT JOIN signups s ON e.id = s.event LEFT JOIN venues v ON e.venue = v.id WHERE e.published = 1 GROUP BY e.id ORDER BY id ASC';
		} else {
			$sql = 'SELECT e.id, e.name, e.date, e.duration, e.signups, v.name AS "venue", e.total_seats,priceInAdv, COUNT(s.id) AS signups FROM events AS e LEFT JOIN signups s ON e.id = s.event LEFT JOIN venues v ON e.venue = v.id GROUP BY e.id ORDER BY id ASC';
		}
		$result = $db->query($sql);

		return $result;
	}

	public static function getAllUpcommingEvents() {
		global $db;

		$sql = 'SELECT e.id, e.published, e.name, e.date, e.duration, v.name "venue" FROM events e, venues v WHERE e.venue = v.id AND date > curdate() ORDER BY date ASC ';
		$result = $db->query($sql);
		$result = $result->fetchAll();

		foreach ($result as $k => $event) {
			// Calculate event ending time.
			$finish = date_create($event['date']);
			$finish->modify('+' . $event['duration'] . ' hours');

			$result[$k]['date'] = formatDtString($result[$k]['date']);
			$result[$k]['finish'] = formatDt($finish);
		}

		return $result;
	}

	public static function getAllPreviousEvents() {
		global $db;

		$sql = 'SELECT name, id, published FROM events WHERE date <= curdate() ORDER BY date DESC';

		$result = $db->query($sql);
		$result->execute();

		return $result->fetchAll();
	}
}

?>
