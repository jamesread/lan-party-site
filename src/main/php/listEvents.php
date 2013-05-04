<?php

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';
require_once 'includes/classes/Events.php';

use \libAllure\Session;

$tpl->assign('privViewUnpublishedEvents', Session::hasPriv('VIEW_UNPUBLISHED_EVENTS'));

$tpl->assign('events', Events::getAllUpcommingEvents());
$tpl->display('eventsListUpcomming.tpl');

$tpl->assign('events', Events::getAllPreviousEvents());
$tpl->display('eventsListPast.tpl');

require_once 'includes/widgets/footer.php';


?>
