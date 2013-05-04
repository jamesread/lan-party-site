<?php

require_once 'includes/common.php';
require_once 'includes/classes/FormSiteSettings.php';

use \libAllure\Session;

requirePrivOrRedirect('SITE_SETTINGS');

$f = new FormSiteSettings();

if ($f->validate()) {
	$f->process();
}

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

$tpl->assignForm($f);
$tpl->display('form.tpl');

require_once 'includes/widgets/footer.php';

?>
