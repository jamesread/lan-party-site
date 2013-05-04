<?php

require_once 'includes/common.php';

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

use \libAllure\DatabaseFactory;

$sql = 'SELECT v.id, v.name, count(e.id) AS usageCount FROM venues v LEFT OUTER JOIN events e ON v.id = e.venue GROUP BY v.id';
$stmt = DatabaseFactory::getInstance()->prepare($sql);
$stmt->execute();

$listVenues = $stmt->fetchAll();

$tpl->assign('listVenues', $listVenues);
$tpl->display('listVenues.tpl');

require_once 'includes/widgets/footer.php';
?>
