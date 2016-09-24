<?php

use \libAllure\HtmlLinksCollection;

$menu = new HtmlLinksCollection('Gallery admin');
$menu->addIfPriv('GALLERY_EDIT', 'updateGallery.php?id=' . $_REQUEST['id'], 'Update gallery');
$menu->addIfPriv('UPLOAD_GALLERY_IMAGE', 'html5uploadImage.php?gallery=' . $_REQUEST['id'], 'Upload images');

if ($menu->hasLinks()) {
	$tpl->assign('links', $menu);
	$tpl->display('sidebarLinks.tpl');
}

?>
