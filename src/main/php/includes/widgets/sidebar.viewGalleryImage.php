<?php

use \libAllure\HtmlLinksCollection;
use \libAllure\Session;

$menu = new HtmlLinksCollection('Gallery image');

if ($image['inDatabase']) {
	$menu->addIf(Session::hasPriv('GALLERY_UPDATE_IMAGE'), 'gallery.php?mode=editImage&amp;gallery=' . $gallery['id'] . '&amp;filename=' . $image['filename'], 'Edit database entry');
	$menu->addIf(Session::hasPriv('GALLERY_SET_COVER_IMAGE'), 'gallery.php?mode=makeCoverImage&amp;filename=' . $image['filename'] . '&amp;gallery=' . $gallery['id'], 'Make this the gallery cover image</a>');
} else {
	$menu->addIf(Session::hasPriv('GALLERY_CREATE_IMAGE'), 'gallery.php?mode=addImage&amp;gallery=' . $gallery['id'] . '&amp;filename=' . $image['filename'], 'Add database entry for image</a>');
}

$menu->addIfPriv('GALLERY_DELETE_IMAGE', 'deleteGalleryImage.php?filename=' . $image['filename'] . '&amp;gallery=' . $gallery['id'], 'Delete');

if ($menu->hasLinks()) {
	$tpl->assign('links', $menu);
	$tpl->display('sidebarLinks.tpl');
}

?>
