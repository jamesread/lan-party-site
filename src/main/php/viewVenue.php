<?php

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

require_once 'includes/classes/ItemVenue.php';

use \libAllure\DatabaseFactory;
use \libAllure\Sanitizer;

$id = Sanitizer::getInstance()->filterUint('id');

$itemVenue = new ItemVenue($id);

$tpl->assign('itemVenue', $itemVenue);
$tpl->assign('listEvents', $itemVenue->getEvents());
$tpl->display('viewVenue.tpl');

require_once 'includes/widgets/footer.php';

?>
