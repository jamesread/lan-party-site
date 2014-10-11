<?php

require_once 'jsonCommon.php';

use \libAllure\DatabaseFactory;
use \libAllure\Sanitizer;

$sql = 'SELECT u.username, m.ip, m.mac FROM authenticated_machines m LEFT JOIN users u ON m.user = u.id WHERE m.event = :eventId';

$stmt = DatabaseFactory::getInstance()->prepare($sql);
$stmt->bindValue(':eventId', Sanitizer::getInstance()->filterUint('event'));
$stmt->execute();

$ipAddresses = $stmt->fetchAll();
$ret = array();

foreach ($ipAddresses as $key => $addr) {
	$ret[$addr['ip']] = $addr;
}

header('Content-Type: application/json');
echo json_encode($ret);;



?>
