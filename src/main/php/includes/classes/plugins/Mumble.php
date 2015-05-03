<?php

require_once 'includes/classes/Events.php';
require_once 'includes/classes/Plugin.php';

require_once 'libAllure/Form.php';

use \libAllure\Form;
use \libAllure\Inflector;
use \libAllure\ElementTextbox;
use \libAllure\Session;

class Mumble implements Plugin {
	public function getSettingsForm() {
		return new FormPluginMumbleSettings();
	}

	private function shouldNotDisplay() {
		if (Session::hasPriv('ADMIN')) {
			$excludesPages = explode("\n", getSiteSetting('plugin.mumble.ignorePages.admin'));
		} else {
			$excludesPages = explode("\n", getSiteSetting('plugin.mumble.ignorePages'));
		}

		return in_array(basename($_SERVER['PHP_SELF']), $excludesPages);
	}

	public function renderSidebar() {
		if ($this->shouldNotDisplay()) {
			return;
		}

		startbox();
		echo '<div class = "mumbleServerView"></div>';
		echo '<script src = "http://tydus.net/MumPI/viewer/resources/javascript/viewer.js"></script>';
		echo '<script type = "text/javascript">$.get("http://tydus.net/MumPI/viewer/ajax.getServerTree.php", {serverId: 1}, drawTree);</script>';
		echo '<div class = "server">';
		echo '<br /><p>Mumble is an open source, low-latency, high quality voice chat software primarily intended for use while gaming. <a href = "http://mumble.sourceforge.net/">Download Mumble.</a>';
		echo '<p><small><strong>Server address/label</strong>: mumble.westlan.co.uk<br /><strong>Port:</strong> 64738</small></p>';
		echo '</div>';

		stopbox('Mumble');
	}
}

class FormPluginMumbleSettings extends Form {
	public function __construct() {
		parent::__construct('mumbleSettings', 'Mumble settings');

		$this->addElement(new ElementTextbox('excludePages', 'Exclude pages', getSiteSetting('plugin.mumble.ignorePages')));
		$this->addElement(new ElementTextbox('excludePagesAdmin', 'Exclude pages as admin', getSiteSetting('plugin.mumble.ignorePages.admin')));
		$this->addDefaultButtons();
	}

	public function process() {
		setSiteSetting('plugin.mumble.ignorePages', $this->getElementValue('excludePages'));
		setSiteSetting('plugin.mumble.ignorePages.admin', $this->getElementValue('excludePagesAdmin'));
	}
}



?>
