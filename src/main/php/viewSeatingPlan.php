<?php

require_once 'includes/widgets/header.php';

use \libAllure\Sanitizer;
use \libAllure\DatabaseFactory;


$id = Sanitizer::getInstance()->filterUint('id');

$sql = 'SELECT sp.id, sp.name, sp.layout FROM seatingplans sp WHERE sp.id = :id LIMIT 1';
$stmt = DatabaseFactory::getInstance()->prepare($sql);
$stmt->bindValue(':id', $id);
$stmt->execute();

$seatingPlan = $stmt->fetchRow();
$structSp = parseSeatingPlanObjects($seatingPlan['layout']);
$structSp = applySeatOwnershipToPlan($structSp);

$tpl->assign('itemSeatingPlan', $seatingPlan);
$tpl->assign('listSeatingPlanObjects', $structSp);
$tpl->display('viewSeatingPlan.tpl');

require_once 'includes/widgets/footer.php';

?> 
