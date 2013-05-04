<?php

require_once 'includes/common.php';

use \libAllure\Session;

if (!Session::hasPriv('VIEW_GROUPS')) {
	$tpl->error('You dont have permission to view groups.');	
}

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

$sql = 'SELECT g.id, g.title, g.css, count(mem.id) membershipCount FROM groups g LEFT JOIN group_memberships mem ON mem.`group` = g.id GROUP BY g.id';
$stmt = $db->prepare($sql);
$stmt->execute();

$tpl->assign('listGroups', $stmt->fetchAll());
$tpl->display('listGroups.tpl');

require_once 'includes/widgets/footer.php';

?>
