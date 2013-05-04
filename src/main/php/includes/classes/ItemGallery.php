<?php

use \libAllure\DatabaseFactory;
use \libAllure\Session;

class ItemGallery extends DatabaseItem {
	private $id;
	private $title;
	private $fullPath;
	private $thumbPath;
	private $status;

	public function __construct($id = null) {
		parent::__construct($id, 'galleries');
		$this->id = $id;

		$this->populatePaths($this->getField('folderName'));
	}

	protected function loadFromArray(array $stuff) {
		$this->id = $stuff['id'];
		$this->title = $stuff['title'];
		$this->populatePaths($stuff['folderName']);
		$this->coverImage = $stuff['coverImage'];
		$this->status = $stuff['status'];
	}

	public function populatePaths($folderName) {
		$this->fullPath = 'resources/images/galleries/' . $folderName . '/full/';
		$this->thumbPath = 'resources/images/galleries/' . $folderName . '/thumb/';

		$this->setField('fullPath', $this->fullPath);
		$this->setField('thumbPath', $this->thumbPath);
	}

	public function fetchImages() {
		if (!file_exists($this->fullPath)) {
			throw new Exception('Gallery path does not exist: ' . $this->fullPath);
		}

		$sql = 'SELECT i.id, i.filename, i.published FROM images i WHERE i.gallery = :gallery';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':gallery', $this->id);
		$stmt->execute();

		$databaseImages = assignKeys($stmt->fetchAll(), 'filename');
		$privViewUnpublished = Session::hasPriv('GALLERY_VIEW_UNPUBLISHED');

		$images = array();

		foreach (scandir($this->fullPath) as $filename) {
			if (strpos($filename, '.') == 0) {
				continue;
			}

			$potentialImage = array(
				'filename' => $filename,
				'published' => true,
			);

			$dbEntry = &$databaseImages[$filename];
			$dbEntry = is_array($dbEntry) ? $dbEntry : array();

			$imageMerged = array_merge($potentialImage, $dbEntry);

			if ($imageMerged['published'] || (!$imageMerged['published'] && $privViewUnpublished)) {
				$images[] = $imageMerged;
			}
		}

		return $images;
	}

	public static function fromArray($stuff) {
		$g = new ItemGallery();
		$g->loadFromArray($stuff);

		return $g;
	}

	public function getFields() {
		return array(
			'id' => $this->id,
			'title' => $this->title,
			'fullPath' => $this->fullPath,
			'thumbPath' => $this->thumbPath,
			'coverImage' => $this->coverImage,
			'status' => $this->status,
		);
	}
}

?>
