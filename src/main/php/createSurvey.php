<?php

require_once 'includes/common.php';
require_once 'includes/classes/FormSurveyCreate.php';
require_once 'libAllure/FormHandler.php';

use \libAllure\FormHandler;

$handler = new FormHandler('FormSurveyCreate');
$handler->setRedirect('listSurveys.php', 'Your survey has been created.');
$handler->handle();

?>
