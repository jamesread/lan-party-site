<?php

require_once 'includes/classes/Plugin.php';

use \libAllure\Form;
use \libAllure\Inflector;
use \libAllure\ElementTextbox;
use \libAllure\Session;

class ContactStaff implements Plugin {
	public function getSettingsForm() {
		return new FormPluginContactStaffSettings();
	}

	private function shouldNotDisplay() {
		if (Session::hasPriv('ADMIN')) {
			$excludesPages = explode("\n", getSiteSetting('plugin.contactStaff.ignorePages.admin'));
		} else {
			$excludesPages = explode("\n", getSiteSetting('plugin.contactStaff.ignorePages'));
		}

		return in_array(basename($_SERVER['PHP_SELF']), $excludesPages);
	}

	public function renderSidebar() {
		if ($this->shouldNotDisplay()) {
			return;
		}

		startbox();
		echo '<p>' . getSiteSetting('plugin.contactStaff.message') . '</p>';
		stopbox(getSiteSetting('plugin.contactStaff.title'));
	}
}

class FormPluginContactStaffSettings extends Form {
	public function __construct() {
		parent::__construct('contactStaffSettings', 'Contact Staff settings');

		$this->addElement(new ElementTextbox('excludePages', 'Exclude pages', getSiteSetting('plugin.contactStaff.ignorePages')));
		$this->addElement(new ElementTextbox('excludePagesAdmin', 'Exclude pages as admin', getSiteSetting('plugin.contactStaff.ignorePages.admin')));
		$this->addElement(new ElementTextbox('title', 'Title', getSiteSetting('plugin.contactStaff.title')));
		$this->addElement(new ElementTextbox('message', 'Message', getSiteSetting('plugin.contactStaff.message')));
		$this->addDefaultButtons();
	}

	public function process() {
		setSiteSetting('plugin.contactStaff.ignorePages', $this->getElementValue('excludePages'));
		setSiteSetting('plugin.contactStaff.ignorePages.admin', $this->getElementValue('excludePagesAdmin'));
		setSiteSetting('plugin.contactStaff.title', $this->getElementValue('title'));
		setSiteSetting('plugin.contactStaff.message', $this->getElementValue('message'));
	}
}



?>

