<?php

require_once 'includes/widgets/header.php';

use \libAllure\Session;
use \libAllure\Sanitizer;
use \libAllure\DatabaseFactory;

Session::requirePriv('DELETE_GALLERY_IMAGE');

$filename = Sanitizer::getInstance()->filterString('filename');
$gallery = Sanitizer::getInstance()->filterUint('gallery');

$image = Galleries::getImage($filename, $gallery);

if ($image == false) {
	redirect('index.php', 'Image does not exist.');
}

if (is_int($gallery) && $image['inDatabase'] && !empty($filename)) {
		$sql = 'DELETE FROM images WHERE filename = :filename AND gallery = :gallery';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':filename', $filename);
		$stmt->bindValue(':gallery', $gallery);
		$stmt->execute();

		@unlink($image['fullPath']);
		@unlink($image['thumbPath']);
}

redirect('viewGallery.php?id=' . $image['galleryId'], 'Image deleted');

require_once 'includes/widgets/footer.php';

?>
