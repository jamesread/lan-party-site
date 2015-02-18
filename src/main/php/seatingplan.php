<?php

require_once 'includes/common.php';

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';
require_once 'includes/classes/FormSeatingPlanMoveUser.php';
require_once 'includes/classes/FormSwapSeats.php';

use \libAllure\DatabaseFactory;
use \libAllure\Sanitizer;
use \libAllure\Session;

if (Session::hasPriv('SEATING_PLAN_MOVE_USERS')) {
	$formMoveUsers = new FormSeatingPlanMoveUser();
	
	if ($formMoveUsers->validate()) {
		$formMoveUsers->process();
	}

	$formSwapUsers = new FormSwapSeats();

	if ($formSwapUsers->validate()) {
		$formSwapUsers->process();
	}
}

$event = Sanitizer::getInstance()->filterUint('event');
$event = Events::getById($event);

if (empty($event['seatingPlan'])) {
	$tpl->error('Seating plan not enabled.');
}

$sql = 'SELECT sp.seatCount, sp.layout, sp.name, e.id AS event, e.name AS eventName FROM events e JOIN seatingplans sp ON e.seatingPlan = sp.id WHERE e.id = :event';
$stmt = DatabaseFactory::getInstance()->prepare($sql);
$stmt->bindValue(':event', $event['id']);
$stmt->execute();

$seatingPlan = $stmt->fetchRow();
$structSp = parseSeatingPlanObjects($seatingPlan['layout']);

$tpl->assign('listSeatingPlanObjects', $structSp);
$tpl->assign('itemSeatingPlan', $seatingPlan);
$tpl->display('viewSeatingPlan.tpl');

if (isset($formMoveUsers)) {
	$tpl->assignForm($formMoveUsers);
	$tpl->display('form.tpl'); 
}

if (isset($formSwapUsers)) {
	$tpl->assignForm($formSwapUsers);
	$tpl->display('form.tpl'); 
}


require_once 'includes/widgets/footer.php';

?>
