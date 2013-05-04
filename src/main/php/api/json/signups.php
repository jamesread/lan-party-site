<?php

require_once 'jsonCommon.php';

require_once 'libAllure/Sanitizer.php';

header('Content-Type: application/json');

use \libAllure\Sanitizer;

$id = Sanitizer::getInstance()->filterUint('id');
$signups = Events::getSignupsForEvent($id);

echo json_encode(getSignupStatistics($signups));

?>
