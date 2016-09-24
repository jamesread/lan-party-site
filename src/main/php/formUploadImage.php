<?php

require_once 'includes/widgets/header.php';

use \libAllure\FormHandler;
use \libAllure\Form;
use \libAllure\Session;
use \libAllure\ElementSelect;
use \libAllure\DatabaseFactory;
use \libAllure\ElementFile;

requirePrivOrRedirect('UPLOAD_GALLERY_IMAGE');

class FormUploadImage extends Form {
	private $directorySettings = array();
	private $directoryAliases = array();

	public function __construct() {
		parent::__construct('uploadImage', 'Upload Image');

		$this->enctype = 'multipart/form-data';
		
		$this->directorySettings['gallery'] = array(
			'maxWidth' => 800,
			'maxHeight' => 600,
		);

		$this->directorySettings['schedule'] = array(
			'maxWidth' => 16,
			'maxHeight' => 16,
		);

		$this->addElement($this->getElementImageDirectories());
		$this->addElement(new ElementFile('file', 'File', null));
//		$this->getElement('file')->isImage = true;
		$this->getElement('file')->destinationDir = 'tempUploads';
		$this->getElement('file')->setMaxImageBounds(1000, 1000);
		$this->addDefaultButtons();
	}

	private function validateFilename() {
		$directory = $this->getElement('dir')->getValue();

		if (!in_array($directory, array_keys($this->directoryAliases))) {
			$this->getElement('dir')->setValidationError('Not a valid directory alias');
			return;
		}

		$this->details = $this->directoryAliases[$directory];

		if (!in_array($this->details['settings'], array_keys($this->directorySettings))) {
			$this->getElement('dir')->setValidationError('No settings found for that directory.');
			return;
		}

		$settings = $this->directorySettings[$this->details['settings']];

		$this->getElement('file')->setMaxImageBounds($settings['maxWidth'], $settings['maxHeight']);
		$this->getElement('file')->destinationDir = $this->details['path']; 
		$this->getElement('file')->validateInternals();
	}

	private function getElementImageDirectories() {
		$el = new ElementSelect('dir', 'Directory');

		if (Session::getUser()->hasPriv('UPLOAD_GALLERY_IMAGE')) {
			$sql = 'SELECT g.id, e.name, g.folderName, g.title FROM events e JOIN galleries g ON e.gallery = g.id ORDER BY e.date';
			$stmt = DatabaseFactory::getInstance()->prepare($sql);
			$stmt->execute();

			foreach ($stmt->fetchAll() as $gallery) {
				$this->directoryAliases['gallery' . $gallery['id']] = array( 
					'path' => 'resources/images/galleries/' . $gallery['folderName'],
					'settings' => 'gallery',
				);

				$el->addOption('Event: ' . $gallery['title'], 'gallery' . $gallery['id']);
			}
		}

		if (Session::getUser()->hasPriv('UPLOAD_SCHEDULE_ICON')) {
			$this->directoryAliases['schedule'] = array(
				'path' => 'resources/icons/games/',
				'settings' => 'schedule',
			);

			$el->addOption('Schedule Icons', 'schedule');
		}

		return $el;
	}

	public function validateExtended() {
		if ($this->getElement('file')->wasAnythingUploaded()) {
			$this->validateFilename();
		}
	}

	public function process() {
		$filename = uniqid() . '.png';

		$this->getElement('file')->destinationFilename = 'full/' . $filename;
		$this->getElement('file')->savePng();

		$this->getElement('file')->resize(100, 100);
		$this->getElement('file')->destinationFilename = 'thumb/' . $filename;
		$this->getElement('file')->savePng();

		$gal = intval(str_replace('gallery', null, $this->getElementValue('dir')));

		if ($gal != null) {
			createGalleryDbEntry($filename, $gal);
		}

		logActivity(Session::getUser()->getUsername() . ' uploaded image ' . $filename . ' to gallery: ' . $this->getElement('file')->destinationDir);

		redirect('account.php', 'Image has been uploaded, thanks!');
	}
}

$fh = new FormHandler('FormUploadImage');
$fh->handle();

require_once 'includes/widgets/footer.php';

?>
