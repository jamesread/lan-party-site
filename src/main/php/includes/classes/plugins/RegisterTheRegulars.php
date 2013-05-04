<?php

require_once 'includes/classes/Plugin.php';

use \libAllure\Form;
use \libAllure\ElementTextbox;
use \libAllure\Session;

class RegisterTheRegulars implements Plugin {
	public function getSettingsForm() {
		return new FormAddRegular();
	}

	public function renderSidebar() {
		global $event, $signups, $db;

		if (!Session::hasPriv('FORCE_SIGNUPS')) {
			return;
		}

		if (!isset($event) || empty($signups)) {
			return;
		}

		$sql = 'SELECT u.id, u.username FROM plugin_regulars r INNER JOIN users u ON r.user = u.id WHERE r.user NOT IN (SELECT s.user FROM signups s WHERE s.event = :eventId) ';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':eventId', $event['id']);
		$stmt->execute();

		$regularUsers = $stmt->fetchAll();
		
		startBox();
		if (count($regularUsers) > 0) {
			echo '<p>The following regulars are not signed up:</p> ';

			echo '<ul>';

			foreach ($regularUsers as $user) {
				echo '<li><a href = "profile.php?id=' . $user['id'] . '">' . $user['username'] . '</a> (<span class = "dummyLink" onclick = "document.getElementById(\'username\').value = \'' . $user['username'] . '\'" >force</span>)</li>';
			}

			echo '</ul>';
		} else {
			echo 'All the regulars are signed up to this event!';
		}


		echo '<p><a href = "plugins.php">Plugin admin</a></p>';
		stopBox('Regulars');
	}
}

class FormAddRegular extends Form {
	public function __construct() {
		parent::__construct('formRegular', 'Regulars');

		$existingRegulars = $this->getExistingRegulars();

		$this->addElement(new ElementTextbox('regularsUsernames', 'Username', $existingRegulars, 'Enter a list of usernames separated by commas. The usernames will checked for valid usernames when you submit the form.'));
		$this->addDefaultButtons();
	}

	public function validateExtended() {
		$this->validateUsernameList();
	}

	private function validateUsernameList() {
		$matches = preg_match('#([a-z0-9]+ ?,?)+#', $this->getElementValue('regularsUsernames'));

		if ($matches === 0) {
			$this->setElementError('regularsUsernames', 'This should be a list of usernames, separated by spaces.');
		}
	}

	public function process() {
		global $db;

		$sql = 'TRUNCATE plugin_regulars';
		$db->query($sql);

		$newRegulars = array();
		
		foreach (explode(',', $this->getElementValue('regularsUsernames')) as $user) {
			$newRegulars[] = '"' . trim($user) . '"';
		}		

		$sql = 'INSERT INTO plugin_regulars (user) SELECT u.id FROM users u WHERE u.username IN (' . implode($newRegulars, ',') . ') ';
		$stmt = $db->prepare($sql);
		$stmt->execute();

		
	}

	private function getExistingRegulars() {
		global $db;

		$sql = 'SELECT u.username FROM plugin_regulars r INNER JOIN users u ON r.user = u.id ';
		$stmt = $db->prepare($sql);
		$stmt->execute();

		$users = array();

		foreach ($stmt->fetchAll() as $user) {
			$users[] = $user['username'];
		}

		return implode($users, ',');
	}
}

?>
