<?php

require_once 'includes/common.php';
require_once 'includes/classes/FormSudo.php';
require_once 'libAllure/FormHandler.php';

requirePrivOrRedirect('SUDO');

use \libAllure\FormHandler;
use \libAllure\Sanitizer;

$handler = new FormHandler('formSudo', $tpl);
$handler->setConstructorArgument(0, Sanitizer::getInstance()->filterString('username'));
$handler->setRedirect('index.php');;
$handler->handle();

?>
