<?php

use \libAllure\Form;
use \libAllure\ElementTextbox;
use \libAllure\DatabaseFactory;

class FormFlagEmails extends Form {
	public function __construct() {
		parent::__construct('formBulkFlag', 'Bulk Flag email');

		$this->addElement(new ElementTextbox('bulkEmail', 'Email addresses separated by comma'));
		$this->addDefaultButtons();
	}

	public function parseEmails() {
		$emails = array();

		foreach (explode("\n", $this->getElementValue('bulkEmail')) as $line) {
			foreach (explode(",", $line) as $email) {
				$emails[] = trim($email);
			}
		}

		return $emails;
	}

	public function process() {
		foreach ($this->parseEmails() as $email) {
			$sql = 'SELECT u.id, u.email, u.username FROM users u WHERE u.email = :email LIMIT 1';
			$stmt = DatabaseFactory::getInstance()->prepare($sql);
			$stmt->bindValue(':email', $email);
			$stmt->execute();

			$user = $stmt->fetchRow();

			if (!empty($user)) {
				echo 'Flagged email ' . $user['email'] . ' that belongs to ' . $user['username'] . '<br />' ;

				$sql = 'UPDATE users u SET u.emailFlagged = 1 WHERE u.id = :uid';
				$stmt = DatabaseFactory::getInstance()->prepare($sql);
				$stmt->bindValue(':uid', $user['id']);
				$stmt->execute();
			}
		}

		echo '<a href = "account.php">return to account</a>';
	}
}

?>
