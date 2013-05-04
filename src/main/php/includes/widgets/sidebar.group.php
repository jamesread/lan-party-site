<?php

use \libAllure\HtmlLinksCollection;
use \libAllure\Session;

$linksCollection = new HtmlLinksCollection('Group Admin');

if (isset($group)) {
	$linksCollection->addIf(Session::hasPriv('EDIT_GROUP_PRIVILEGES'), 'group.php?action=privileges&amp;id=' . $group->getId(), 'Privileges');
	$linksCollection->addIf(Session::hasPriv('EDIT_GROUP_SETTINGS'), 'group.php?action=edit&amp;id=' . $group->getId(), 'Settings', null, 'siteSettings');
	$linksCollection->addIf(Session::hasPriv('GROUP_DELETE'), 'group.php?action=delete&amp;id=' . $group->getId(), 'Delete', null, 'delete');
} else {
	$linksCollection->addIf(Session::hasPriv('GROUP_CREATE'), 'group.php?action=create', 'Create');
}

$tpl->assign('links', $linksCollection);
$tpl->display('sidebarLinks.tpl');

?>
