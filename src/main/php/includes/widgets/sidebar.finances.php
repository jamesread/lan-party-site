<?php

use \libAllure\HtmlLinksCollection;

$links = new HtmlLinksCollection();
$links->add('form.php?form=FormCreateFinanceEntry', 'Create');
$links->add('listFinanceAccounts.php', 'Finance accounts');
$tpl->assign('links', $links);
$tpl->display('sidebarLinks.tpl');

?>
