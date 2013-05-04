<?php

require_once 'includes/widgets/header.php';

use \libAllure\User;
use \libAllure\Session;
use \libAllure\Sanitizer;

if (!Session::isLoggedIn()) {
	redirect('index.php', 'Guests do not have attendance records.');
}

if (!Session::hasPriv('VIEW_ATTENDANCE')) {
	redirect('account.php', 'Do you not have permission to view your attendance record');
}

if (!isset($_REQUEST['user'])) {
	$user = Session::getUser();
} else {
	$user = User::getUserById(Sanitizer::getInstance()->filterUint('user'));
}

$attendance = getUserSignups($user->getId());

require_once 'includes/widgets/sidebar.php';

$tpl->assign('stats', getSignupStatistics($attendance));
$tpl->assign('username', $user->getUsername());
$tpl->assign('userId', $user->getId());
$tpl->assign('attendance', $attendance);
$tpl->assign('privViewSignupComments', Session::hasPriv('VIEW_SIGNUP_COMMENTS'));
$tpl->display('attendanceRecord.tpl');

require_once 'includes/widgets/footer.php';

?>
