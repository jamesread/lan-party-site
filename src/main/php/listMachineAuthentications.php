<?php

require_once 'includes/widgets/header.php';

use \libAllure\DatabaseFactory;

$sql = 'SELECT m.mac, m.ip, e.name AS eventName, e.id AS eventId, m.seat, u.username, u.id AS userId FROM authenticated_machines m JOIN users u ON m.user = u.id JOIN events e ON m.event = e.id';
$stmt = DatabaseFactory::getInstance()->prepare($sql);
$stmt->execute();

$tpl->assign('listAuthentications', $stmt->fetchAll());
$tpl->display('listMachineAuthentications.tpl');

require_once 'includes/widgets/footer.php';

?>
