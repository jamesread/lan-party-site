<?php

use \libAllure\Form;
use \libAllure\ElementHidden;
use \libAllure\ElementCheckbox;
use \libAllure\ElementInput;
use \libAllure\Sanitizer;
use \libAllure\DatabaseFactory;

class FormGalleryImageEdit extends Form {
	public function __construct($gallery, $filename) {
		parent::__construct('editImageForm');

		$sanitizer = new Sanitizer();
		$gallery = $sanitizer->filterUint('gallery');
		$filename = $sanitizer->filterString('filename');

		$sql = 'SELECT i.filename, i.gallery, i.caption, i.promo, i.published, g.title FROM images i, galleries g WHERE i.gallery = g.id AND g.id = :gallery AND i.filename = :filename LIMIT 1';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':gallery', $gallery);
		$stmt->bindValue(':filename', $filename);
		$stmt->execute();

		if ($stmt->numRows() == 0) {
			throw new Exception('Image entry not found in the database. The image may exist on the filesystem.');
		}

		$current = $stmt->fetchRow();

		$this->addElement(new ElementHidden('mode', null, 'editImage'));
		$this->addElement(new ElementHidden('gallery', null, $gallery));
		$this->addElement(new ElementHidden('filename', null, $filename));
		$this->addElement(new ElementInput('caption', 'Caption', $current['caption']));
		$this->addElement(new ElementCheckbox('promo', 'Promotional image', $current['promo'], 'Is this image a promotional image? Promotional images are used on the homepage.'));
		$this->addElement(new ElementCheckbox('published', 'Published', $current['published']));

		$this->addButtons(Form::BTN_SUBMIT);

		$this->setTitle('<a href = "gallery.php">Galleries</a> &raquo; Gallery: <a href = "viewGallery.php?id=' . $gallery . '">' . $current['title'] . '</a> &raquo Edit image');
	}

	public function process() {
		$sql = 'UPDATE images SET promo = :promo, caption = :caption, published = :published WHERE filename = :filename AND gallery = :gallery ';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':promo', $this->getElementValue('promo'));
		$stmt->bindValue(':caption', $this->getElementValue('caption'));
		$stmt->bindValue(':filename', $this->getElementValue('filename'));
		$stmt->bindValue(':published', $this->getElementValue('published'));
		$stmt->bindValue(':gallery', $this->getElementValue('gallery'));
		$stmt->execute();
	}
}

?>
