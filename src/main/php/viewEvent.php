<?php

require_once 'includes/common.php';
require_once 'includes/classes/Events.php';
require_once 'includes/classes/Schedule.php';

use \libAllure\Session;
use \libAllure\Sanitizer;

$id = Sanitizer::getInstance()->filterUint('id');

try {
	$event = Events::getById($id);
} catch (Exception $e) {
	$tpl->error('Could not get event');
}

$event['priceInAdvWithCurrency'] = doubleToGbp($event['priceInAdv']);
$event['priceOnDoorWithCurrency'] = doubleToGbp($event['priceOnDoor']);

//$tpl->clear_assign('form');
if (Session::hasPriv('FORCE_SIGNUP')) {
	require_once 'includes/classes/FormForceSignup.php';

	$formForceSignup = new FormForceSignup($event['id']);

	if ($formForceSignup->validate()) {
		$formForceSignup->process();
	}
}

require_once 'includes/widgets/header.php';

if (!Session::hasPriv('VIEW_SIGNUP_COMMENTS')) {
	require_once 'includes/widgets/sidebar.php';
}

if (Session::isLoggedIn()) {
	$notifications = array();
	checkNotificationNotGuarenteedSeats($notifications);
	$tpl->assign('notifications', $notifications);
}

$signups = Events::getSignupsForEvent($id, $event['signups']);

$tpl->assign('event', $event);
$tpl->assign('signups', $signups);
$tpl->assign('signupLinks', signupLinks($event['id'], $event['signups'], null));
$tpl->assign('signupStatistics', getSignupStatistics($signups));
$tpl->display('eventOverview.tpl');

if (Session::hasPriv('FORCE_SIGNUP')) {
	$tpl->assignForm($formForceSignup);
}

if (Session::hasPriv('EVENT_FINANCE_OVERVIEW')) {
	$tpl->assign('eventFinanceOverview', Events::getSignupFinances($event['id']));
}

$tpl->assign('privViewAttendance', Session::hasPriv('VIEW_ATTENDANCE_COUNTS'));
$tpl->assign('privViewSignupComments', Session::hasPriv('VIEW_SIGNUP_COMMENTS'));
$tpl->display('signupsList.tpl');

if (Session::hasPriv('VIEW_SIGNUP_COMMENTS')) {
	require_once 'includes/widgets/sidebar.php';
}

$tpl->assign('venue', array('id' => $event['venueId'], 'name' => $event['venueName']));
$tpl->display('venueOverview.tpl');

if (Session::hasPriv('SCHEDULE_UPDATE')) {
	$action = Sanitizer::getInstance()->filterString('action');

	if ($action == 'delete') {
		$id = intval($_REQUEST['schId']);

		$sql = 'DELETE FROM event_schedule WHERE id = :id ';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':id', $id);
		$stmt->execute();
	}

	require_once 'includes/classes/FormScheduleAdd.php';

	$f = new FormScheduleAdd($event['id']);

	if ($f->validate()) {
		$f->process();
		$f->reset();
	}
}

$schedule = new Schedule($event['id']);
$tpl->assign('privEditSchedule', Session::hasPriv('SCHEDULE_UPDATE'));
$tpl->assign('schedule', $schedule->fetch());

if (Session::hasPriv('SCHEDULE_CHANGE')) {
	$tpl->assignForm($f);
}

$tpl->display('schedule.tpl');

require_once 'includes/widgets/footer.php';

?>
