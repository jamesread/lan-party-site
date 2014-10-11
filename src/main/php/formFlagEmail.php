<?php

require_once 'includes/common.php';
require_once 'includes/classes/FormFlagEmails.php';

requirePrivOrRedirect('FLAG_EMAILS');

use \libAllure\FormHandler;

$handler = new FormHandler('FormFlagEmails', $tpl);
$handler->handle();

?>
