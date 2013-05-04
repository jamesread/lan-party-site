<?php

use \libAllure\Form;
use \libAllure\ElementHidden;
use \libAllure\ElementInput;
use \libAllure\ElementNumeric;
use \libAllure\ElementAlphaNumeric;
use \libAllure\ElementSelect;
use \libAllure\Sanitizer;

class FormGalleryEdit extends Form {
	public function __construct() {
		parent::__construct('editGallery', 'Edit Gallery');

		$gallery = Galleries::getById(Sanitizer::getInstance()->filterUint('id'));

		$this->addElement(new ElementHidden('mode', null, 'edit'));
		$this->addElement(new ElementHidden('id', null, $gallery['id']));
		$this->addElement(new ElementInput('title', 'Title', $gallery['title']));
		$this->addElement(new ElementInput('folderName', 'Folder Name', $gallery['folderName']));
		$this->addElement(new ElementInput('coverImage', 'Cover Image', $gallery['coverImage'], 'The filename of the THUMBNAIL already in the gallery that will be the cover image.'));
		$this->addElement(new ElementNumeric('ordinal', 'Ordinal', $gallery['ordinal'], 'Used for organizing the gallery.'));
		$this->addElement(new ElementAlphaNumeric('description', 'Description', $gallery['description'], 'A description that is shown when people view the gallery.'));
		$this->getElement('description')->setPunctuationAllowed(true);
		$this->getElement('description')->setMinMaxLengths(0, 64);

		$elStatus = new ElementSelect('status', 'Status', $gallery['status']);
		$elStatus->addOption('Open');
		$elStatus->addOption('Closed');
		$elStatus->addOption('Staff');
		$this->addElement($elStatus);

		$this->addDefaultButtons();
	}

	public function process() {
		global $db;

		$sql = 'UPDATE galleries SET title = :title, folderName = :folderName, coverImage = :coverImage, ordinal = :ordinal, description = :description, status = :status WHERE id = :id';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':title', $this->getElementValue('title'));
		$stmt->bindValue(':folderName', $this->getElementValue('folderName'));
		$stmt->bindValue(':coverImage', $this->getElementValue('coverImage'));
		$stmt->bindValue(':ordinal', $this->getElementValue('ordinal'));
		$stmt->bindValue(':description', $this->getElementValue('description'));
		$stmt->bindValue(':status', $this->getElementValue('status'));
		$stmt->bindValue(':id', $this->getElementValue('id'));
		$stmt->execute();

		return true;
	}
}

?>
