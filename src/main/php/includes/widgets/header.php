<?php

require_once 'includes/common.php';

use \libAllure\Session;
use \libAllure\HtmlLinksCollection;

global $tpl, $db;

if (Session::isLoggedIn()) {
	$tpl->assign('avatar', 'resources/images/avatars/' . Session::getUser()->getId() . '.png');
	$tpl->assign('username', Session::getUser()->getUsername());
	$tpl->assign('userId', Session::getUser()->getId());
} else {
	$tpl->assign('avatar', 'resources/images/defaultAvatar.png');
	$tpl->assign('username', 'Guest');
}

	// We don't output all the naviagion and whatnot if there has been some sort of error.
	if (isset($_GET['error']) || basename($_SERVER['PHP_SELF']) == 'error.php') {
		return;
	}

$sql = 'SELECT i.title, i.url FROM additional_menu_items i ';
$stmt = $db->query($sql);

$ll = new HtmlLinksCollection();
foreach ($stmt->fetchAll() as $link) {
	$ll->add($link['url'], $link['title']);
}

if (!empty($_SESSION['userHidden'])) {
	$tpl->assign('userHidden', $_SESSION['userHidden']->getUsername());
}

$tpl->assign('promo', 'resources/themes/westlan.ng/images/logo.png');
$tpl->assign('IS_LOGGED_IN', Session::isLoggedIn());
$tpl->assign('additionalLinks', $ll);
$tpl->assign('globalAnnouncement', getSiteSetting('globalAnnouncement'));
$tpl->assign('newsFeatureEnabled', getSiteSetting('newsFeature'));
$tpl->assign('galleryFeatureEnabled', getSiteSetting('galleryFeature'));
$tpl->assign('notification', SessionBasedNotifications::getInstance()->pop());
$tpl->assign('isMobileBrowser', isMobileBrowser());
$tpl->assign('theme', getSiteSetting('theme'));
$tpl->assign('siteTitle', getSiteSetting('siteTitle'));
$tpl->assign('siteDescription', getSiteSetting('siteDescription'));
$tpl->display('header.tpl');

?>
