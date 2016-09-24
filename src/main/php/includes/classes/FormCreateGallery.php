<?php

use \libAllure\Form;
use \libAllure\ElementInput;
use \libAllure\ElementAlphaNumeric;
use \libAllure\DatabaseFactory;

class FormCreateGallery extends Form {
	public function __construct() {
		parent::__construct('createGallery', 'Create gallery');

		$this->addElement(new ElementInput('title', 'Title'));
		$this->addElement(new ElementAlphaNumeric('folderPath', 'Folder Name', null, 'Letters and numbers only.'));
		$this->getElement('folderPath')->setMinMaxLengths(1, 64);

		$this->requireFields('title', 'folderPath');

		$this->addDefaultButtons();
	}

	protected function validateExtended() {
		$folderPath = $this->getElementValue('folderPath');

		try {
			mkdirOrException('resources/images/galleries/' . $folderPath);
			mkdirOrException('resources/images/galleries/' . $folderPath . '/full/');
			mkdirOrException('resources/images/galleries/' . $folderPath . '/thumb/');
		} catch (Exception $e){ 
			$this->setElementError('folderPath', 'Could not create directory: ' . $e->getMessage());
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
