<?php

require_once 'includes/common.php';
require_once 'includes/classes/FormUpdateFinanceAccount.php';
require_once 'libAllure/FormHandler.php';

use \libAllure\FormHandler;

$handler = new FormHandler('FormUpdateSeatingPlan', $tpl);
$handler->setRedirect('listSeatingPlans.php');
$handler->handle();

?>
