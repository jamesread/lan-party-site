<?php

require_once 'includes/common.php';

requirePrivOrRedirect('CREATE_FINANCE_ACCOUNT');

$_REQUEST['form'] = 'FormCreateFinanceAccount';
require_once 'form.php';

?>
