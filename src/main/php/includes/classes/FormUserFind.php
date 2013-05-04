<?php

use \libAllure\Form;
use \libAllure\ElementInput;

class FormUserFind extends Form {
	private $results;

	public function __construct() {
		parent::__construct('findUser', 'Find User');

		$this->addElement(new ElementInput('search', 'Search'));

		$this->addButtons(Form::BTN_SUBMIT);
	}

	public function process() {
		global $db;

		$username = $this->getElementValue('search');
		$username = $db->escape($username);

		$sql = 'SELECT id, username, real_name, lastLogin FROM users WHERE username LIKE "%' . $username . '%" ';
		$this->results = $db->query($sql);
	}

	public function getResults() {
		return $this->results;
	}
}

?>
