<?php

use \libAllure\Form;
use \libAllure\ElementTextbox;
use \libAllure\Session;

class FormAdditionalMenuItems extends Form {
	public function __construct() {
		parent::__construct('formAdditionalMenuItems', 'Additional Menu Items');

		Session::requirePriv('ADDITIONAL_MENU_ITEMS');

		$this->addElement(new ElementTextbox('links', 'Links', $this->getLinks(), 'Format: title=url&lt;newline&gt;...'));

		$this->addDefaultButtons();
	}

	private function getLinks() {
		global $db;

		$sql = 'SELECT i.title, i.url FROM additional_menu_items i';
		$stmt = $db->query($sql);
		
		$stuff = null;

		foreach ($stmt->fetchAll() as $link) {
			$stuff .= $link['title'] . '=' . $link['url'] . "\n";
		}

		return $stuff;
	}

	private function parseLinks() {
		$links = array();

		foreach (explode("\n", $this->getElementValue('links')) as $line) {
			$line = explode('=', $line);

			if (count($line) != 2) {
				continue;
			}

			list($title, $url) = $line;

			$links[] = array(
				'title' => trim($title),
				'url' => trim($url),
			);			
		}

		return $links;
	}

	public function process() {
		global $db;

		$sql = 'TRUNCATE additional_menu_items ';
		$stmt = $db->prepare($sql);
		$stmt->execute();

		$sql = 'INSERT INTO additional_menu_items (title, url) VALUES (:title, :url) ';
		$stmt = $db->prepare($sql);
		
		foreach ($this->parseLinks() as $link) {
			$stmt->bindValue(':title', $link['title']);
			$stmt->bindValue(':url', $link['url']);
			$stmt->execute();
		}

		redirect('account.php', 'Jiggled.');
	}
}

?>
