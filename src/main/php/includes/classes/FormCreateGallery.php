<?php

use \libAllure\Form;
use \libAllure\ElementInput;
use \libAllure\ElementAlphaNumeric;
use \libAllure\DatabaseFactory;

class FormCreateGallery extends Form {
	public function __construct() {
		parent::__construct('createGallery', 'Create gallery');

		$this->addElement(new ElementInput('title', 'Title'));
		$this->addElement(new ElementAlphaNumeric('folderPath', 'Folder Path'));
		$this->getElement('folderPath')->setMinMaxLengths(1, 64);

		$this->requireFields('title', 'folderPath');

		$this->addDefaultButtons();
	}

	protected function validateExtended() {
		$folderPath = $this->getElementValue('folderPath');

		if (!is_dir('resources/images/galleries/' . $folderPath)) {
			$created = @mkdir('resources/images/galleries/' . $folderPath);
			@mkdir('resources/images/galleries/' . $folderPath . '/full');
			@mkdir('resources/images/galleries/' . $folderPath . '/thumbs');

			if (!$created) {
				$this->setElementError('folderPath', 'That directory does not exist (under resources/images/galleries/) and it could not be created.');
			}
		}
	}

	public function process() {
		$sql = 'INSERT INTO galleries (title, status, folderName) VALUES (:title, "Open", :folderName)';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue('title', $this->getElementValue('title'));
		$stmt->bindValue(':folderName', $this->getElementValue('folderPath'));
		$stmt->execute();
	}
}

?>
