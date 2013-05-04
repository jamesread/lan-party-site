<?php

use \libAllure\HtmlLinksCollection;
use \libAllure\Session;

$linksCollection = new HtmlLinksCollection('Event admin');
//$linksCollection->addIf(Session::hasPriv('EVENT_DELETE'), 'deleteEvent.php?id=' . $_REQUEST['id'], 'Delete event', null, 'delete');
$linksCollection->addIf(Session::hasPriv('EVENT_UPDATE'), 'updateEvent.php?id=' . $event['id'], 'Update');
$linksCollection->addIf(Session::hasPriv('ADMIN_SEATING'), 'seatingplan.php?event=' . $event['id'], 'Seating plan');


if ($linksCollection->hasLinks()) {
	$tpl->assign('links', $linksCollection);
	$tpl->display('sidebarLinks.tpl');
}


?>
