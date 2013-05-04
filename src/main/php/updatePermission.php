<?php

require_once 'includes/common.php';

use \libAllure\FormHandler;

$handler = new FormHandler('FormUpdatePermission', $tpl);
$handler->setRedirect('listPermissions.php');
$handler->handle();

?>
