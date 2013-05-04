<?php

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

use \libAllure\Session;

if (!Session::hasPriv('GALLERY_SCAN')) {
	throw new PermissionsException();
}

if (!isset($_REQUEST['reallyDoIt'])) {
	$tpl->assign('title', 'Scan image gallery');
	$msg = '';
	$msg .= '<p>This page allows you to scan the image gallery for problems like missing thumbnails or badly named directories. This scans the entire gallery, can take a while and put a load on the webserver. Try to do it at quiet times.</p>';
	$msg .= '<p><a href = "?mode=scanImageGallery&reallyDoIt=yuppers">Okay, scan the gallery!</a></p>';
	$tpl->assign('message', $msg);
	$tpl->display('notification.tpl');

	require_once 'includes/widgets/footer.php';
}

$galleryDir = scandir('resources/images/galleries/');
$results = array();

foreach ($galleryDir as $gallery) {
	Galleries::checkDirectory($gallery, $results);
}

if (count($results) == 0) {
	$tpl->assign('title', 'Gallery scan');
	$tpl->assign('message', 'There were no errors found in the gallery scan! Yay! Now you can go and play outside!');
	$tpl->display('notification.tpl');
} else {
	$tpl->assign('Gallery scan');
	$tpl->assign('message', 'Uh oh, there were errors');
	$tpl->display('notification.tpl');

	echo '<div class = "box"><pre>';
	echo implode('<br />', $results);
	echo '</pre></div>';
}

require_once 'includes/widgets/footer.php';

?>
