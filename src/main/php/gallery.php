<?php

require_once 'includes/common.php';
require_once 'includes/classes/Galleries.php';
require_once 'includes/classes/FormGalleryEdit.php';
require_once 'includes/classes/ItemGallery.php';

use \libAllure\Sanitizer;
use \libAllure\Session;

$sanitizer = new Sanitizer();
$mode = $sanitizer->filterString('mode');

if (!getSiteSetting('galleryFeature')) {
	redirect('index.php', 'Gallery feature is disabled.');
}

switch ($mode) {
case 'editImage';
	requirePrivOrRedirect('GALLERY_UPDATE_IMAGE');

	require_once 'updateGalleryImage.php';
	break;
case 'addImage':
	requirePrivOrRedirect('GALLERY_CREATE_IMAGE');

	$gallery = intval($_REQUEST['gallery']);
	$filename = $_REQUEST['filename'];

	$sql = 'INSERT INTO images (gallery, filename) VALUES (:gallery, :filename) ';
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':gallery', $gallery);
	$stmt->bindValue(':filename', $filename);
	$stmt->execute();

	redirect('viewGalleryImage.php?filename=' . $filename . '&amp;gallery=' . $gallery, 'Image added to database.');
	break;
case 'makeCoverImage';
	requirePrivOrRedirect('GALLERY_SET_COVER_IMAGE');

	$gallery = intval($_REQUEST['gallery']);

	$sql = 'UPDATE galleries SET coverImage = :filename WHERE id = :gallery ';
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':filename', $_REQUEST['filename']);
	$stmt->bindValue(':gallery', $gallery);
	$stmt->execute();

	redirect('viewGallery.php?id=' . $gallery, 'Gallery cover image updated');

	break;
case null:
default:
}

require_once 'includes/widgets/footer.php';

?>
