<?php

require_once 'includes/common.php';
require_once 'includes/classes/FormUpdateFinanceAccount.php';
require_once 'libAllure/FormHandler.php';

use \libAllure\FormHandler;

$handler = new FormHandler('FormUpdateFinanceAllocator', $tpl);
$handler->setRedirect('listFinanceAccounts.php');
$handler->handle();

?>
