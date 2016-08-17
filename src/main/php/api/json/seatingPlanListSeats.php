<?php

set_include_path(get_include_path() . PATH_SEPARATOR . '../../');
require_once 'includes/common.php';
require_once 'includes/functions.seatingPlan.php';

use \libAllure\Sanitizer;
use \libAllure\DatabaseFactory;
use \libAllure\Session;

$eventId = Sanitizer::getInstance()->filterUint('event');

$seatChanges = array();

foreach (getSeats($eventId) as $seatSelection) {
	$seatSelection['username'] = utf8_encode($seatSelection['username']);
	$seatChanges[] = getJsonSeatChange('set', $seatSelection['seat'], $seatSelection['username'], $seatSelection['usernameCss'], $seatSelection['seatCss']);
}

header('Content-Type: application/json');
echo json_encode($seatChanges);
?> 
