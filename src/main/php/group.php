<?php

require_once 'includes/common.php';
require_once 'includes/classes/Group.php';
require_once 'includes/classes/FormUpdateGroupPrivileges.php';
require_once 'includes/classes/FormGroupEdit.php';
require_once 'includes/classes/FormGroupCreate.php';

use \libAllure\Sanitizer;
use \libAllure\Session;
use \libAllure\DatabaseFactory;
use \libAllure\ElementHidden;
use \libAllure\User;

$sanitizer = new Sanitizer();
$action = $sanitizer->filterString('action');

switch ($action) {
case 'makePrimary':
	Session::requirePriv('GROUP_PRIMARY');

	$groupId = $sanitizer->filterUint('group');
	$userId = $sanitizer->filterUint('user');

	$sql = 'UPDATE users SET `group` = :groupId WHERE id = :userId LIMIT 1';
	$stmt = DatabaseFactory::getInstance()->prepare($sql);
	$stmt->bindValue(':groupId', $groupId);
	$stmt->bindValue(':userId', $userId);
	$stmt->execute();

	redirect('profile.php?id=' . $userId, 'Primary group changed for user.');
	break;
case 'delete':
	Session::requirePriv('GROUP_DELETE');

	try {
		$id = $sanitizer->filterUint('id');
		$group = new Group($id);
	} catch (Exception $e) {
		$tpl->error('Group not found');
	}

	if ($group->getMemberCount() > 0) {
		$tpl->error('This group is not empty, it cannot be deleted while it still has members.');
	}

	$sql = 'DELETE FROM groups WHERE id = :id LIMIT 1';
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':id', $id);
	$stmt->execute();
	redirect('group.php', 'Group deleted.');

	require_once 'includes/widgets/footer.php';

	break;
case 'revoke':
	$priv = $sanitizer->filterUint('priv');
	$groupId = $sanitizer->filterUint('group');

	$sql = 'DELETE FROM privileges_g WHERE permission = :priv AND `group` = :groupId ';
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':priv', $priv);
	$stmt->bindValue(':groupId', $groupId);
	$stmt->execute();

	redirect('group.php?action=view&amp;id=' . $groupId, 'Permision revoked');

	break;
case 'kick':
	Session::requirePriv('GROUP_KICK');

	$group = new Group($sanitizer->filterUint('group'));
	$user = User::getUserById($sanitizer->filterUint('user'));

	$sql = 'DELETE FROM group_memberships WHERE user = :userId AND `group` = :groupId LIMIT 1';
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':userId', $user->getId());
	$stmt->bindValue(':groupId', $group->getId());
	$stmt->execute();

	redirect('group.php?action=view&amp;id=' . $group->getId(), 'User kicked from group.');

	break;
case 'edit':
	$id = $sanitizer->filterUint('id');
	$group = new Group($id);

	$f = new FormGroupEdit();
	$f->addElement(new ElementHidden('action', null, 'edit'));
	
	if ($f->validate()) {
		$f->process();
	}

	require_once 'includes/widgets/header.php';

	$tpl->assignForm($f);
	$tpl->display('form.tpl');
	
	break;
case 'privileges':
	$id = $sanitizer->filterUint('id');
	$group = new Group($id);

	$f = new FormUpdateGroupPrivileges($id);
	$f->addElement(new ElementHidden('action', null, 'privileges'));

	if ($f->validate()) {
		$f->process();
	}

	require_once 'includes/widgets/header.php';

	$tpl->assignForm($f);
	$tpl->display('form.tpl');

	break;
case 'view':
	$id = $sanitizer->filterUint('id');
	$group = new Group($id);

	require_once 'includes/widgets/header.php';
	require_once 'includes/widgets/sidebar.php';

	$tpl->assign('group', $group->getArray());
	$tpl->assign('groupMembers', $group->getMembers());
	$tpl->assign('groupPrivilegesList', $group->getPrivs());
	$tpl->display('viewGroup.tpl');

	break;
case 'create':
	Session::requirePriv('GROUP_CREATE');

	$f = new FormGroupCreate();
	$f->addElement(new ElementHidden('action', null, 'create'));

	if ($f->validate()) {
		$f->process();
	}

	require_once 'includes/widgets/header.php';
	require_once 'includes/widgets/sidebar.php';

	$tpl->assignForm($f);
	$tpl->display('form.tpl');

	break;
default:	
	throw new Exception();
}

require_once 'includes/widgets/footer.php';

?>
