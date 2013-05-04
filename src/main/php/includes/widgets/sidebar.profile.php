<?php

use \libAllure\HtmlLinksCollection;
use \libAllure\Session;

if (!Session::isLoggedIn()) {
	return;
}

$isMe = Session::getUser()->getId() == $user->getId() && Session::hasPriv('CHANGE_AVATAR');

$linksCollection = new HtmlLinksCollection('User admin');
$linksCollection->addIf(Session::hasPriv('DELETE_USER'), 'users.php?action=delete&amp;id=' . $user->getId(), 'Delete', null, 'delete');
$linksCollection->addIf(Session::hasPriv('VIEW_ATTENDANCE'), 'viewAttendance.php?user=' . $user->getId(), 'Attendance');
$linksCollection->addIf(Session::hasPriv('EDIT_USER') || $isMe, 'users.php?action=edit&amp;user=' . $user->getId(), ($isMe) ? 'Update my profile' : 'Edit user', null, 'update');
$linksCollection->addIf(Session::hasPriv('SEND_EMAIL'), 'sendEmail.php?userId=' . $user->getId(), 'Send email');
$linksCollection->addIf(Session::hasPriv('EDIT_OTHERS_AVATAR') || $isMe, 'updateAvatar.php?user=' . $user->getId(), 'Avatar', null, 'avatar');
$linksCollection->addIfPriv('SUDO', 'formSudo.php?username=' . $user->getUsername(), 'SUDO');

if ($linksCollection->hasLinks()) {
	$tpl->assign('links', $linksCollection);
	$tpl->display('sidebarLinks.tpl');
}

?>
