<?php

require_once 'includes/common.php';
require_once 'includes/classes/FormSearchMachineAuthentications.php';

requirePrivOrRedirect('SEARCH_MACHINE_AUTHS');

use \libAllure\FormHandler;

$handler = new FormHandler('SearchMachineAuthentications', $tpl);
$handler->handle();

if ($handler->getForm()->validate()) {
	echo 'list';
}

?>
