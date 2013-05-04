<?php

require_once 'includes/common.php';
require_once 'includes/classes/FormAddUserToGroup.php';

use \libAllure\Session;
use \libAllure\User;

try {
	if (isset($_REQUEST['id'])) {
		$user = User::getUserById($_REQUEST['id']);
	} else {
		$user = Session::getUser();
	}
} catch (Exception $e) {
	$tpl->error('Could not find user.');
}


if (Session::hasPriv('GROUP_EDIT')) {
	$formAddUserToGroup = new FormAddUserToGroup($user->getId());

	if ($formAddUserToGroup->validate()) {
		$formAddUserToGroup->process();
	}
}

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

$userArray = array(
	'username' => $user->getData('username'),
	'realName' => $user->getData('real_name'),
	'registered' => $user->getData('registered')
);

$avatarUrl = 'resources/images/avatars/' . $user->getId() . '.png';

if (file_exists($avatarUrl)) {
	$userArray['avatar'] = $avatarUrl;	
}


if (Session::isLoggedIn() && Session::getUser()->hasPriv('VIEW_PROFILE_PRIVATE')) {
	$userArray['canSeePrivate'] = true;
	$userArray['lastLogin'] = $user->getData('lastLogin');
	$userArray['email'] = $user->getData('email');

	$bannedReason = $user->getData('bannedReason');
	$userArray['isBanned'] = !empty($bannedReason);
	$userArray['bannedReason'] = $bannedReason;
} else {
	$userArray['canSeePrivate'] = false;
}


$tpl->assign('user', $userArray);
$tpl->display('profile.tpl');

if (Session::hasPriv('PRIVS_VIEW')) {
	$listPermissions = array();

	foreach ($user->getPrivs() as $privilege) {
		if ($privilege['source'] == 'Group') {
			$source = 'Group: <a href = "group.php?action=view&amp;id=' . $privilege['sourceId'] . '">' . $privilege['sourceTitle'] . '</a>';
		} else {
			$source = 'User';
		}

		$privilege['source'] = $source;
		$listPermissions[] = $privilege;
	}

	$tpl->assign('listPermissions', $listPermissions);
	$tpl->display('profilePermissions.tpl');
}


if (Session::hasPriv('GROUPS_VIEW')) {
	$usergroups = array();

	foreach ($user->getUsergroups() as $group) {
		$actions = array();

		if (Session::hasPriv('GROUPS_EDIT')) {
			if ($user->getData('group') != $group['id']) {
				$actions[] = '<a href = "group.php?action=makePrimary&amp;user=' . $user->getId() . '&amp;group=' . $group['id'] . '">Make primary</a>';
				$actions[] = '<a href = "group.php?action=kick&amp;user=' . $user->getId() . '&amp;group=' . $group['id'] . '">Kick</a>';
			}
		}

		$group['actions'] = implode(', ', $actions);

		$usergroups[] = $group;
	}
	
	$tpl->assign('usergroups', $usergroups);
	$tpl->assignForm($formAddUserToGroup);
	$tpl->display('profileUsergroups.tpl');
}

require_once 'includes/widgets/footer.php';

?>
