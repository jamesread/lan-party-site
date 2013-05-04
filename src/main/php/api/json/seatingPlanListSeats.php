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
	$seatChanges[] = getJsonSeatChange('set', $seatSelection['seat'], $seatSelection['username']);
}

echo json_encode($seatChanges);
?> 
