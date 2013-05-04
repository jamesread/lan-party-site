<?php

require_once 'includes/common.php';
require_once 'includes/classes/FormContentEdit.php';

use \libAllure\Session;

if (!Session::hasPriv('CONTENT_EDIT')) {
	$tpl->error('You do not have permission to edit the content.');
}

if (isset($_REQUEST['id'])) {
	$f = new FormContentEdit($_REQUEST['id']);
} else {
	$f = new FormContentEdit();
}

if ($f->validate()) {
	$f->process();

	redirect('listContent.php', 'Content updated.');
} else {
	if (isset($_REQUEST['title'])) {
		$f->getElement('title')->setValue($_REQUEST['title']);
	}
}

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

$tpl->assignForm($f);
$tpl->display('form.tpl');
$tpl->display('wikiHelp.tpl');

require_once 'includes/widgets/footer.php';

?>
