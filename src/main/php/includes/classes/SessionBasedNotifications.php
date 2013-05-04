<?php

class SessionBasedNotifications {
	private static $instance = null;

	private function __construct() {
	}

	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new SessionBasedNotifications();
		}

		return self::$instance;
	}

	private function checkStructures() {
		if (!isset($_SESSION['notifications']) || !is_array($_SESSION['notifications'])) {
			$this->clear();
		}
	}

	public function clear() {
		$_SESSION['notifications'] = array();
	}

	public function add($message, $karma = 0) {
		$this->checkStructures();

		$_SESSION['notifications'][] = array(
			'message' => $message,
			'karma' => $karma
		);
	}

	public function pop() {
		if (!isset($_SESSION['notifications'])) {
			return null; 
		}

		if (!is_array($_SESSION['notifications'])) {
			return null;
		}

		if (count($_SESSION['notifications']) == 0) {
			return null; 
		}

		$notification = array_pop($_SESSION['notifications']);

		return $notification;
	}
}

?>
