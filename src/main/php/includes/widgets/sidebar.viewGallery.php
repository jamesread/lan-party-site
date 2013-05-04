<?php

use \libAllure\HtmlLinksCollection;

$menu = new HtmlLinksCollection('Gallery admin');
$menu->addIfPriv('GALLERY_EDIT', 'updateGallery.php?id=' . $_REQUEST['id'], 'Update gallery');

if ($menu->hasLinks()) {
	$tpl->assign('links', $menu);
	$tpl->display('sidebarLinks.tpl');
}

?>
