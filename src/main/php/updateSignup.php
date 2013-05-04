<?php

require_once 'includes/common.php';
require_once 'includes/classes/FormSignupEdit.php';

$f = new FormSignupEdit();

if ($f->validate()) {
	$f->process();
}

require_once 'includes/widgets/header.php';

$tpl->assignForm($f);
$tpl->display('form.tpl');

require_once 'includes/widgets/footer.php';

?>
