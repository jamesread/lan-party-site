<?php

require_once 'includes/common.php';

use \libAllure\Session;
use \libAllure\User;
use \libAllure\Sanitizer;
use \libAllure\SimpleFatalError;

if (!Session::isLoggedIn()) {
	$tpl->error('You must be logged in to view the page.');
}

$users = User::getAllLocalUsers();

$sanitizer = new Sanitizer();
$action = $sanitizer->filterString('action');

switch ($action) {
case 'delete';
	$id = Sanitizer::getInstance()->filterUint('id');

	if ($id == Session::getUser()->getId()) {
		throw new SimpleFatalError('Err, you cannot delete yourself. Try jumping off a tall building instead.');
	}

	if ($id == -1) {
		throw new SimpleFatalError('Woooah! Are you trying to make the world explode? You cannot delete the SYSTEM user account!');
	}

	if (!Session::getUser()->hasPriv('USER_DELETE')) {
		throw new SimpleFatalError('Oh gnoes! You dont have permission to do that.');
	}

	$sql = 'DELETE FROM users WHERE id = "' . $id . '" LIMIT 1 ';
	$result = $db->query($sql);

	logActivity('User deleted: ' . $id);

	redirect('users.php', 'Used deleted... I hope they dont mind..');

	break;
case 'edit':
	require_once 'includes/common.php';
	require_once 'includes/classes/FormUpdateProfile.php';
	
	$userId = Sanitizer::getInstance()->filterUint('user');
	$f = new FormUpdateProfile($userId);
	
	if ($f->validate()) {
		$f->process();
		redirect('profile.php?id=' . $userId, 'User edited.');
	}

	require_once 'includes/widgets/header.php';
	require_once 'includes/widgets/sidebar.php';

	$tpl->assignForm($f);
	$tpl->display('form.tpl');

	require_once 'includes/widgets/footer.php';
	break;
default:
	require_once 'includes/widgets/header.php';
	require_once 'includes/widgets/sidebar.php';

	requirePrivOrRedirect('VIEW_USERS', 'index.php');

	$tpl->assign('listUsers', $users);
	$tpl->display('listUsers.tpl');
}

require_once 'includes/widgets/footer.php';

?>
