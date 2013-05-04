<?php

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';
require_once 'includes/classes/Events.php';
require_once 'includes/classes/Galleries.php';

$nextEvent = Events::nextEvent();

$tpl->assign('nextEvent', $nextEvent);
$tpl->assign('signups', getSignupStatistics(Events::getSignupsForEvent($nextEvent['id'])));
$tpl->display('home.tpl');

require_once 'includes/widgets/footer.php';

?>
