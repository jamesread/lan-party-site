<?php

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';
require_once 'includes/classes/FormPermissionCreate.php';

use \libAllure\Session;

if (!Session::getUser()->hasPriv('VIEW_PRIVS')) {
	box('You do not have permission to view this page.');

	require_once 'includes/widgets/footer.php';
}

$sql = 'SELECT `key`, description FROM permissions ORDER BY `key` ASC';
$result = $db->query($sql);
$permissions = array();

while ($perm = $result->fetchRow()) {
	if (Session::getUser()->hasPriv($perm['key'])) {
		$priv = '<span class = "good">' . $perm['key'] . '</span>'; 
	} else {
		if (Session::hasPriv('VIEW_UNASSIGNED_PERMISSIONS')) {
			$priv = '<span class = "bad">' . $perm['key'] . '</span>';
		}
	}

	$perm['priv'] = $priv;

	$permissions[] = $perm;
}

$tpl->assign('permissionsList', $permissions);

$tpl->display('listPermissions.tpl');

require_once 'includes/widgets/footer.php';

?>
