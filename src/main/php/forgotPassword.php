<?php

require_once 'includes/common.php';
require_once 'includes/classes/FormResetPassword.php';


if (isset($_REQUEST['state'])) {
	$state = intval($_REQUEST['state']);
} else {
	$state = FormResetPassword::STATE_USER_PROVIDE_EMAIL;
}

if ($state == FormResetPassword::STATE_USER_PROVIDE_EMAIL) {
	require_once 'includes/widgets/header.php';
}

$f = new FormResetPassword($state);

if ($f->validate()) {
	$f->process();

	redirect('login.php', 'Thanks, your new password has been emailed to you.');
}
	
if ($state == FormResetPassword::STATE_USER_PROVIDE_SECRET) {
	require_once 'includes/widgets/header.php';
}

$tpl->assignForm($f);
$tpl->display('form.tpl');

require_once 'includes/widgets/footer.php';

?>
