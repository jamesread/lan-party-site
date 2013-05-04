<?php

require_once 'includes/common.php';

use \libAllure\Sanitizer;

$sanitizer = new Sanitizer();

$gallery = Galleries::GetById($sanitizer->filterUint('gallery'));
$image = Galleries::getImage($sanitizer->filterString('filename'), $gallery);

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

Galleries::getPrevNext($image['filename'], $gallery, $prev, $next, $cii, $count);

$tpl->assign('imageNumber', ($cii + 1));
$tpl->assign('imageCount', ($count));
$tpl->assign('prevFilename', $prev);
$tpl->assign('nextFilename', $next);
$tpl->assign('image', $image);
$tpl->assign('gallery', $gallery);

if (strpos($image['filename'], '.jpg') != null) {
	$tpl->assign('exifData', \libAllure\array_flatten(exif_read_data($gallery['fullPath'] . $image['filename'])));
} else {
	$tpl->assign('exifData', null);
}

$tpl->display('viewGalleryImage.tpl');

require_once 'includes/widgets/footer.php';

?>
