<?php

require_once 'includes/common.php';

use \libAllure\Session;

$id = intval($_REQUEST['id']);

if (!(Session::isLoggedIn() && Session::getUser()->hasPriv('EVENT_DELETE'))) {
	throw new PermissionsException();
}

$sql = 'DELETE FROM events WHERE id = :id LIMIT 1';
$stmt = $db->prepare($sql);
$stmt->bindValue(':id', $id);
$stmt->execute();

logActivity('Event deleted');

redirect('listEvents.php', 'Event deleted. Oh dear.');

?>
