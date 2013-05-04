<?php

use \libAllure\Session;
use \libAllure\Database;

class Schedule {
	public function __construct($eventId) {
		$this->eventId = $eventId;
	}

	public function fetch() {
		global $db;

		$sql = 'SELECT es.id, es.message, es.start, es.duration, es.icon FROM event_schedule es WHERE es.event = :event ORDER BY start';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':event', $this->eventId, Database::PARAM_INT);
		$stmt->execute();

		$ret = array();

		foreach ($stmt->fetchAll() as $scheduleItem) {
			$scheduleItem['actions'] = array();

			if (Session::hasPriv('SCHEDULE_CHANGE')) {
				$scheduleItem['actions'][] = '<a href = "?action=delete&amp;schId=' . $scheduleItem['id'] . '&amp;id=' . $this->eventId .'">Delete</a>';
			}

			$scheduleItem['actions'] = implode(', ', $scheduleItem['actions']);
			$scheduleItem['start'] = formatDtString($scheduleItem['start']);

			if (!empty($scheduleItem['icon'])) {
				$scheduleItem['iconUrl'] = 'resources/images/icons/games/' . $scheduleItem['icon'];
			} else {
				$scheduleItem['iconUrl'] = null;
			}

			$ret[] = $scheduleItem;
		}

		return $ret;
	}
}

?>
