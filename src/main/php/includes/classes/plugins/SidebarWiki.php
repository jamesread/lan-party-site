<?php

use \libAllure\Form;
use \libAllure\ElementTextbox;

class SidebarWiki implements Plugin {
	public function __construct() {

	}

	private function getCurrentPage() {
		$queryInfo = parse_url($_SERVER['REQUEST_URI']);
		$pathInfo = pathinfo($queryInfo['path']);
	
		if (!isset($pathInfo['extension'])) {
			$pathInfo['extension'] = null;
		}

		if (isset($queryInfo['query'])) {
			$currentPage = $pathInfo['filename'] . '.' . $pathInfo['extension'] . '?' . $queryInfo['query'];
		} else {
			$currentPage = $pathInfo['filename'] . '.' . $pathInfo['extension'];
		}

		return $currentPage;
	}

	private function shouldRender() {	
		$pages = getSiteSetting('plugins.sidebarWiki.enabledPages');
		$pages = explode("\n", $pages);

		return in_array($this->getCurrentPage(), $pages);
	}

	public function renderSidebar() {
		if (!$this->shouldRender()) {
			return;
		}

		startbox();
		echo getContent('sbWiki.' . $this->getCurrentPage());
		stopbox();
	}

	public function getSettingsForm() {
		return new FormSidebarWikiSettings();
	}
}

class FormSidebarWikiSettings extends Form {
	public function __construct() {
		parent::__construct('sidebarWikiSettings', 'Sidebar wiki settings');
		$this->addElement(new ElementTextbox('enabledPages', 'Enabled pages', getSiteSetting('plugins.sidebarWiki.enabledPages')));
		$this->addDefaultButtons();
	}

	public function process() {
		setSiteSetting('plugins.sidebarWiki.enabledPages', $this->getElementValue('enabledPages'));
	}
}


?>
