<?php

use \libAllure\HtmlLinksCollection;
use \liballure\Sanitizer;

$sanitizer = new Sanitizer();

$menu = new HtmlLinksCollection('View Finance Account');
$menu->add('form.php?form=FormCreateFinanceEntry&amp;account=' . $sanitizer->filterUint('id'), 'Create finance entry');
$menu->add('updateFinanceAccount.php?id=' . $sanitizer->filterUint('id'), 'Update');
$menu->addIf(($sanitizer->filterUint('id') != 1), 'deleteFinanceAccount.php?id=' . $sanitizer->filterUint('id'), 'Delete');


$tpl->assign('links', $menu);
$tpl->display('sidebarLinks.tpl');

?>
