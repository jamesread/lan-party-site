<?php

require_once 'includes/common.php';
require_once 'includes/classes/FormUpdateProfile.php';

requireLogin();

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

use \libAllure\Session;
use \libAllure\HtmlLinksCollection;
use \libAllure\DatabaseFactory;

if (!Session::isLoggedIn()) {
	loginPrompt();
}

$notifications = array();
if (Session::hasPriv('APPROVE_GALLERY_IMAGE')) {
	$sql = 'SELECT i.filename, g.id AS gallery, g.title AS galleryTitle FROM images i LEFT JOIN galleries g ON i.gallery = g.id WHERE i.user_uploaded != 0 AND i.published = 0 ';
	$stmt = DatabaseFactory::getInstance()->prepare($sql);
	$stmt->execute();

	$unpublishedUserUploadedImages = $stmt->fetchAll();

	foreach ($unpublishedUserUploadedImages as $image) {
		$notifications[] = 'Image <a href = "viewGalleryImage.php?filename=' . $image['filename'] . '&gallery=' . $image['gallery'] . '">' . $image['filename'] . '</a> in gallery ' . $image['galleryTitle'] . ', uploaded by a user, is unpublished. Please publish or delete.';
	}
}
$tpl->assign('notifications', $notifications);

$tpl->assign('emailFlagged', Session::getUser()->getData('emailFlagged'));
$tpl->assign('username', Session::getUser()->getUsername());

$standardLinks = new HtmlLinksCollection();
$standardLinks->addIf(Session::hasPriv('CHANGE_AVATAR'), 'updateAvatar.php', 'Avatar', 'avatar');
$standardLinks->addIf(Session::hasPriv('VIEW_ATTENDANCE'), 'viewAttendance.php', 'Attendance');
$standardLinks->addIfPriv('UPLOAD_GALLERY_IMAGE', 'formUploadImage.php', 'Upload gallery image');

$tpl->assign('standardLinks', $standardLinks);

$privilegedLinks = new HtmlLinksCollection();
$privilegedLinks->addIfPriv('ADMIN_USERS', 'users.php', 'Users', 'users');
$privilegedLinks->addIfPriv('ADMIN_GROUPS', 'listGroups.php', 'Groups');
$privilegedLinks->addIfPriv('VIEW_PRIVS', 'listPermissions.php', 'Permissions');
$privilegedLinks->addIfPriv('VIEW_VENUES', 'listVenues.php', 'Venues');
$privilegedLinks->addIfPriv('EDIT_CONTENT', 'listContent.php', 'Content blocks', 'contentBlocks');
$privilegedLinks->addIfPriv('VIEW_LOG', 'listLogs.php', 'Log');
$privilegedLinks->addIfPriv('MAILING_LIST', 'viewMailingList.php', 'Mailing list');
$privilegedLinks->addIfPriv('ADMIN_SURVEYS', 'listSurveys.php', 'Survey', 'survey');
$privilegedLinks->addIfPriv('SITE_SETTINGS', 'siteSettings.php', 'Site settings', 'siteSettings');
$privilegedLinks->addIfPriv('ADMIN_PLUGINS', 'plugins.php', 'Plugins');
$privilegedLinks->addIfPriv('ADDITIONAL_MENU_ITEMS', 'form.php?form=FormAdditionalMenuItems', 'Additional menu items');
$privilegedLinks->addIfPriv('FINANCES', 'listFinanceAccounts.php', 'Finances');
$privilegedLinks->addIfPriv('SUDO', 'formSudo.php', 'SUDO');
$privilegedLinks->addIfPriv('VIEW_SYSTEM_STATISTICS', 'viewSystemStatus.php', 'System Status');
$privilegedLinks->addIfPriv('MACHINE_AUTHENTICATIONS', 'listMachineAuthentications.php', 'Machine Authentications');
$privilegedLinks->addIfPriv('LIST_SEATINGPLANS', 'listSeatingPlans.php', 'Seating plans');
$tpl->assign('privilegedLinks', $privilegedLinks);
$tpl->display('account.tpl');

$userEventSignups = getUserSignups();
$userSignupStatistics = getSignupStatistics($userEventSignups);

$tpl->assign('userEventSignups', $userEventSignups);
$tpl->assign('userSignupStatistics', $userSignupStatistics);
$tpl->assign('privViewAttendance', Session::hasPriv('VIEW_ATTENDANCE'));
$tpl->display('accountSignupOverview.tpl');

require_once 'includes/widgets/footer.php';

?>
