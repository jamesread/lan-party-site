<?php

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

$listGalleries = Galleries::getAll();

foreach ($listGalleries as &$itemGallery) {
	$itemGallery = $itemGallery->getFields();
}

$tpl->assign('galleryIntro', getContent('galleryIntro'));
$tpl->assign('listGalleries', $listGalleries);
$tpl->display('listGalleries.tpl');

require_once 'includes/widgets/footer.php';

?>
