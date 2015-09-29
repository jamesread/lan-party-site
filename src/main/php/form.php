<?php

require_once 'includes/common.php';

use \libAllure\ElementHidden;
use \libAllure\Sanitizer;

$sanitizer = new Sanitizer();

$form = $sanitizer->filterAlphaNumeric('form');
$form = new $form();
$form->addElement(new ElementHidden('form', null, get_class($form)));

$redirect = $sanitizer->filterString('redirect');

if (!empty($redirect)) {
	$form->addElement(new ElementHidden('redirect', null, $redirect));
}

if ($form->validate()) {
	$form->process();

	if (!empty($redirect)) {
		redirect($redirect, 'You are being redirected.');
	}
}

require_once 'includes/widgets/header.php';

if (isset($showSidebar)) {
	require_once 'includes/widgets/sidebar.php';
}

$tpl->assignForm($form);
$tpl->display('form.tpl');

require_once 'includes/widgets/footer.php';


?>
