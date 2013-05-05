<?php

use \libAllure\Form;
use \libAllure\ElementAlphaNumeric;
use \libAllure\Session;

class Shoutbox implements Plugin {
	public function getSettingsForm() {
		return new FormShoutboxSettings();
	}

	public function renderSidebar() {
		global $db, $tpl;

		if (Session::isLoggedIn()) {
			$f = new FormShoutbox();

			if ($f->validate()) {
				$f->process();
			}
		}

		startbox();

		$sql = 'SELECT ps.id, u.username, u.id userId, ps.content FROM plugin_shoutbox ps, users u WHERE ps.user = u.id ORDER BY ps.id DESC LIMIT 3';
		$result = $db->query($sql);

		foreach ($result->fetchAll() as $shout) {
		 	echo '<p><a href = "profile.php?id=' . $shout['userId'] . '">' . $shout['username'] . '</a>: ' . $shout['content'] . '</p>';
		}

		if (Session::isLoggedIn()) {
			echo '<hr />';
			$tpl->assignForm($f);
			$tpl->assign('excludeBox', true);
			$tpl->display('form.tpl');
//			$tpl->clearAssign('form');
		}

		stopbox('Shout Box');
	}
}

class FormShoutboxSettings extends Form {
	public function __construct() {
		parent::__construct('formShoutboxSettings', 'Shoutbox settings');

		$this->addDefaultButtons();
	}

	public function process() {}
}

class FormShoutbox extends Form {
	public function __construct() {
		parent::__construct('shoutbox');
		$this->addElement(new ElementAlphaNumeric('shout', 'Shout!'));
		$this->getElement('shout')->setPunctuationAllowed(true);

		$this->copyRequestVar('id');

		$this->addButtons(Form::BTN_SUBMIT);
	}

	private function copyRequestVar($varName) {
		if (isset($_REQUEST[$varName])) {
			$this->addElementHidden($varName, $_REQUEST[$varName]);
		}
	}

	public function validateExtended() {
		global $db;

		$sql = 'SELECT ps.user FROM plugin_shoutbox ps ORDER BY ps.id DESC LIMIT 1';
		$stmt = $db->prepare($sql);
		$stmt->execute();

		if ($stmt->numRows() == 0) {
			return;
		} else {
			$lastPost = $stmt->fetchRow();

			if ($lastPost['user'] == Session::getUser()->getId()) {
				$this->setElementError('shout', 'You were the last poster, wait for somebody else!');	
			}
		}	
	}

	public function process() {
		global $db;

		$sql = 'INSERT INTO plugin_shoutbox (user, content) VALUES (:user, :content) ';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':user', Session::getUser()->getId());
		$stmt->bindValue(':content', $this->getElementValue('shout'));
		$stmt->execute();
	}
}

?>
