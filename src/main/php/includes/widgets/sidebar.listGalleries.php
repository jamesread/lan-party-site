<?php

use \libAllure\HtmlLinksCollection;
use \libAllure\Session;

$menu = new HtmlLinksCollection('Gallery admin');

$menu->addIf(Session::hasPriv('GALLERY_SCAN'), 'doScanImageGallery.php', 'Scan gallery for problems');
$menu->addIf(Session::hasPriv('GALLERY_CREATE'), 'createGallery.php', 'Create');

if ($menu->hasLinks()) {
	$tpl->assign('links', $menu);
	$tpl->display('sidebarLinks.tpl');
}

?>
