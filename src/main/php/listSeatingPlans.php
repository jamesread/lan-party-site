<?php

require_once 'includes/widgets/header.php';

use \libAllure\DatabaseFactory;

$sql = 'SELECT s.id, s.name, count(e.id) AS usedBy FROM seatingplans s LEFT JOIN events e ON s.id = e.seatingPlan GROUP BY s.id';
$stmt = DatabaseFactory::getInstance()->prepare($sql);
$stmt->execute();

$tpl->assign('listSeatingPlans', $stmt->fetchAll());
$tpl->display('listSeatingPlans.tpl');

require_once 'includes/widgets/footer.php';

?> 
