<?php

require_once 'includes/common.php';

require_once 'includes/classes/FormEventUpdate.php';

use \libAllure\Sanitizer;

try {
	$id = Sanitizer::getInstance()->filterUint('id');

	$f = new FormEventUpdate($id);
} catch (Exception $e) {
	$tpl->error('Event not found.');
}

if ($f->validate()) {
	$f->process();

	logAndRedirect('listEvents.php', 'Event updated: ' . $f->getElementValue('name'));
}

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

$tpl->displayForm($f);

require_once 'includes/widgets/footer.php';

?>
