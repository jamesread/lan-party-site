<?php

require_once 'includes/common.php';
require_once 'includes/classes/FormRegistration.php';

$f = new FormRegistration();

if ($f->validate()) {
	$f->process();

	logActivity('User registered: ' . $f->getElementValue('username'), -1);

	redirect('login.php', 'Your account has been created.');
}

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

$tpl->assignForm($f);
$tpl->display('form.tpl');

require_once 'includes/widgets/footer.php';

?>
