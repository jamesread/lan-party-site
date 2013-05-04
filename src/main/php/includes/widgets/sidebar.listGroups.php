<?php

use \libAllure\HtmlLinksCollection;
use \libAllure\Session;

$linksCollection = new HtmlLinksCollection('Group Admin');
$linksCollection->addIf(Session::hasPriv('GROUP_CREATE'), 'group.php?action=create', 'Create');

$tpl->assign('links', $linksCollection);
$tpl->display('sidebarLinks.tpl');

?>
