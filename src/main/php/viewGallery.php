<?php

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

use \libAllure\Sanitizer;
use \libAllure\Session;

$gallery = Galleries::getById(Sanitizer::getInstance()->filterUint('id'));

try {
$files = $gallery->fetchImages();
} catch (Exception $exception) {
	$tpl->error($exception);
}

try {
	$tpl->assign('event', Events::getByGalleryId($gallery['id']));
} catch (Exception $e) {
	$tpl->assign('event', null);
}

$tpl->assign('privViewUnpublished', Session::hasPriv('GALLERY_VIEW_UNPUBLISHED'));
$tpl->assign('files', $files);
$tpl->assign('gallery', $gallery);
$tpl->display('viewGallery.tpl');

require_once 'includes/widgets/footer.php';

?>
