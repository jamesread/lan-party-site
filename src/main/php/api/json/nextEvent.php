<?php

require_once 'jsonCommon.php';

$nextEvent = Events::nextEvent();

outputJson($nextEvent);

?>
