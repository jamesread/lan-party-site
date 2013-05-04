<?php

require_once 'includes/common.php';
require_once 'libAllure/FormHandler.php';

use \libAllure\FormHandler;

$formHandler = new FormHandler('FormPermissionCreate', $tpl);
$formHandler->setRedirect('listPermissions.php');
$formHandler->handle();

?>
