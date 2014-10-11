<?php

require_once 'jsonCommon.php';

$events = Events::getAllEvents()->fetchAll();

if (isset($_REQUEST['format']) && $_REQUEST['format'] == 'csv') {
	header('Content-Type: text/plain');

	echo implode(',', array_keys($events[0]));
	echo ',url';
	echo "\n";

	foreach ($events as $event) {
		echo implode(',', $event);
		echo ',http://www.westlan.co.uk/viewEvent.php?id=' . $event['id'];
		echo"\n";
	}
} else {
	header('Content-Type: application/json');
	echo json_encode(Events::getAllEvents()->fetchAll());
}


?>
