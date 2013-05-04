<?php

use \libAllure\HtmlLinksCollection;
use \libAllure\Session;

$links = new HtmlLinksCollection('Permissions admin');

$links->addIf(Session::hasPriv('CREATE_PERMISSION'), 'createPermission.php', 'Create permission', null, 'create');

$tpl->assign('links', $links);
$tpl->display('sidebarLinks.tpl');

?>

