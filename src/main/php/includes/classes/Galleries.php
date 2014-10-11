<?php

require_once 'includes/classes/ItemGallery.php';

use \libAllure\Session;

class Galleries {
	private static $randomImageCache = array();
	private static $usedRandomImages = array();

	public static function getRandomImage() {
		if (empty(self::$randomImageCache)) {
			global $db;

			$sql = 'SELECT i.filename, g.id AS galleryId, g.folderName FROM images i, galleries g WHERE i.promo = 1 AND i.gallery = g.id ORDER BY rand() ';
			$stmt = $db->query($sql);
			$stmt->execute();

			foreach ($stmt->fetchAll() as $image) {
				self::enrichImage($image);
				self::$randomImageCache[$image['filename']] = $image;
			}
		}

		if (count(self::$usedRandomImages) == count(self::$randomImageCache)) {
			// we ran out of random images!
			return array_rand(self::$usedRandomImages);
		} else {
			$randomImage = array_pop(self::$randomImageCache);
			self::$usedRandomImages[] = $randomImage;

			return $randomImage;
		}
	}

	public static function getImage($filename, $gallery) {
		$image = array(
			'filename' => $filename,
			'inDatabase' => false,
			'caption' => '',
			'published' => 'true',
		);

		$dbEntry = self::getImageFromDatabase($filename, $gallery);

		if (is_array($dbEntry)) {
			$image = array_merge($image, $dbEntry);
			$image['inDatabase'] = true;
		}

		return $image;
	}

	public static function getImageFromDatabase($filename, $gallery) {
		global $db;

		if (is_array($gallery) || is_object($gallery)) {
			$gid = $gallery['id'];
		} else {
			$gid = $gallery;
		}

		$sql = 'SELECT i.filename, i.promo, i.caption, i.published, g.folderName, g.id AS galleryId FROM galleries g, images i WHERE i.filename = :filename AND g.id = :gallery AND i.gallery = g.id LIMIT 1';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':filename', $filename);
		$stmt->bindValue(':gallery', $gid);
		$stmt->execute();

		if ($stmt->numRows() == 0) {
			return false;
		} else {
			$image = $stmt->fetchRow();
			self::enrichImage($image);

			return $image;
		}
	}

	private static function enrichImage(&$image) {
		$image['fullPath'] = 'resources/images/galleries/' . $image['folderName'] . '/full/' . $image['filename'];
		$image['thumbPath'] = 'resources/images/galleries/' . $image['folderName'] . '/thumb/' . $image['filename'];
	}

	public static function getAll() {
		global $db;

		if (Session::hasPriv('SUPERUSER')) {
			$sql = 'SELECT g.id, g.title, g.coverImage, g.folderName, e.date, g.status FROM galleries g LEFT JOIN events e ON e.gallery = g.id ORDER BY e.date DESC, g.title ASC, g.ordinal ASC';
		} else {
			$sql = 'SELECT g.id, g.title, g.coverImage, g.folderName, e.date, g.status FROM galleries g LEFT JOIN events e ON e.gallery = g.id WHERE g.status = "Open" ORDER BY e.date DESC, g.title ASC, g.ordinal ASC';
		}

		$result = $db->query($sql);

		$galleries = array();
		foreach ($result->fetchAll() as $itemGallery) {
			$galleries[] = ItemGallery::fromArray($itemGallery);
		}

		return $galleries;
	}

	public static function getPrevNext($filename, $gallery, &$prev, &$next, &$cii, &$count) {
//		$allImages = scandir($gallery['fullPath']);
		$allImages = $gallery->fetchImages();
		$count = count($allImages);

		// Can we find this file amoungst all the files in the directory?
		$cii = false;
		foreach ($allImages as $index => $currentImage) {
			if ($filename == $currentImage['filename']) {
				$cii = $index; break;
			}
		}

		if ($cii === false) {
			throw new Exception('Could not find the image in a scandir/search. This normally happens when there is a thumbnail, but not a full sized image.');
		}

		$prev = isset($allImages[$cii - 1]) ? $allImages[$cii - 1] : null;
		$next = isset($allImages[$cii + 1]) ? $allImages[$cii + 1] : null;
	}

	public static function getById($id) {
		return new ItemGallery($id);
		global $db;

		$id = intval($id);

		$sql = 'SELECT g.id, g.title, g.folderName, g.status, g.coverImage, g.ordinal, g.description FROM galleries g WHERE g.id = :id LIMIT 1';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':id', $id);
		$stmt->execute();

		if ($stmt->numRows() == 0) {
			throw new Exception('Gallery not found (' . $id . ')');
		}

		$gallery = $stmt->fetchRow();
		$gallery['fullPath'] = 'resources/images/galleries/' . $gallery['folderName'] . '/full/';
		$gallery['thumbPath'] = 'resources/images/galleries/' . $gallery['folderName'] . '/thumb/';

		return $gallery;
	}

	public static function checkDirectory($directory, &$results) {
		if ($directory[0] == '.') {
			return;
		}

		$galleryPath = 'resources' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'galleries' . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR;

		if (!file_exists($galleryPath . 'full')) {
			$results[] = 'The gallery "' . $directory . '" does not have a folder called "full" for the full size images.';
		} else {
			foreach (scandir($galleryPath . 'full') as $file) {
				if ($file[0] == '.') {
					continue;
				}

				self::checkFile($file, $directory . DIRECTORY_SEPARATOR . 'full', $results);

				if (!file_exists($galleryPath . 'thumb/' . $file)) {
					$results[] = 'Fullsize image exists without thumbnail ' . $galleryPath .  '/full/' . $file;
				}
			}
		}

		if (!file_exists($galleryPath . 'thumb')) {
			$results[] = 'The gallery "' . $directory . '" does not have a folder called "thumb" for the thumbnail size images.';
		} else {
			foreach (scandir($galleryPath . 'thumb') as $file) {
				if ($file[0] == '.') {
					continue;
				}

				self::checkFile($file, $directory . DIRECTORY_SEPARATOR . 'thumb', $results);

				if (!file_exists($galleryPath . 'full/' . $file)) {
					$results[] = 'Thumbnail exists without fullsize image <a href = "' . $galleryPath . '/thumb/' . $file . '">' . $galleryPath . '/thumb/' . $file . '</a>';
				}
			}
		}
	}

	private static function checkFile($file, $gallery, &$results) {
		$pathInfo = pathinfo($file);

		if (!preg_match('#^[\w\d\.\-\_]*$#', $file)) {
			$results[] = 'File has bad characters in its name: ' . $file . ' in gallery: ' . $gallery;
		}

		switch($pathInfo['extension']) {
			case 'jpeg';
			case 'jpg';
			case 'png';
			case 'gif';
				return true;
			default:
				$results[] = 'The file "' . $file . '" in the image gallery "' . $gallery . '" has a weird file extension.';
		}
	}
}
