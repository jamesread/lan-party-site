<?php 

use \libAllure\HtmlLinksCollection;

$links = new HtmlLinksCollection('Finance admin');
$links->add('createFinanceAccount.php', 'Create account');
$links->add('updateFinanceAllocator.php', 'Update finance allocator');

$tpl->assign('links', $links);
$tpl->display('sidebarLinks.tpl');

?>
