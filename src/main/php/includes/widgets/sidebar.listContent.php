<?php

use \liballure\HtmlLinksCollection;

$menu = new HtmlLinksCollection('Content admin');
$menu->add('updateContent.php?action=new', 'New block');

$tpl->assign('links', $menu);
$tpl->display('sidebarLinks.tpl');

