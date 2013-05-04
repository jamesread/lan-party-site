<?php

require_once 'includes/common.php';
require_once 'includes/classes/FormUpdateAvatar.php';

use \libAllure\Session;
use \libAllure\Sanitizer;

requireLogin();

if (!Session::hasPriv('CHANGE_AVATAR')) {
	redirect('account.php', 'You do not have permission to change you avatar.');
}

if (isset($_REQUEST['user']) && Session::hasPriv('CHANGE_OTHERS_AVATAR')) {
	$sanitizer = new Sanitizer();
	$user = $sanitizer->filterUint('user');
} else {
	$user = Session::getUser()->getId();
}

$f = new FormUpdateAvatar($user);

if ($f->validate()) {
	$f->process();

	redirect('updateAvatar.php?user=' . $user, 'Avatar updated.');
}

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

echo '<div class = "box"><h2><a href = "account.php">Account</a> &raquo; Avatar</h2>';
$avatar = 'resources/images/avatars/' . $user . '.png';

if (!file_exists($avatar)) {
	$avatar = 'resources/images/defaultAvatar.png';
}

	echo '<div style = "width:20%; display: inline-block; vertical-align: top;">';
	echo '<img src = "' . $avatar .'" alt = "avatar" />';
	echo '</div>';

echo '<div style = "width:40%; display: inline-block; vertical-align: top;">';

$tpl->assign('excludeBox', true);
$tpl->assignForm($f);
$tpl->display('form.tpl');
echo '</div></div>';

require_once 'includes/widgets/footer.php';

?>
