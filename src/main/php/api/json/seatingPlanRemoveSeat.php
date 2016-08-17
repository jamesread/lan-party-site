<?php

set_include_path(get_include_path() . PATH_SEPARATOR . '../../');
require_once 'includes/common.php';
require_once 'includes/functions.seatingPlan.php';

requirePrivOrRedirect('SUPERUSER');

$eventId = Sanitizer::getInstance()->filterUint('event');
$userId = Sanitizer::getInstance()->filterUint('user');

removeSeat($eventId, $userId);

echo 'OK'

?>
