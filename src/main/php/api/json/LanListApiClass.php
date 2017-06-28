<?php

class LanListApi {
	private $version = '1.0.0';

	private $events = array();

	public function addEvent($event) {
		$this->events[] = $event;
	}

	public function respondDefault() {
		$this->outputJson(array(
			'version' => $this->version,
		));
	}

	public function respond() {
		if (!isset($_GET['action'])) {
			$this->respondDefault();
		} else {
			switch($_GET['action']) {
				case 'events':
					$this->outputJson($this->events);
				default:
					$this->respondDefault();
			}
		}
	}

	private function outputJson($contents) {
		header('Content-Type: application/json');
		echo json_encode($contents);
		exit;
	}
}

?>
