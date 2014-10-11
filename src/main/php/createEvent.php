<?php

require_once 'includes/common.php';
require_once 'libAllure/FormHandler.php';

use \libAllure\FormHandler;
use \libAllure\DatabaseFactory;

$sql = 'SELECT v.id FROM venues v';
$venuesCount = count(DatabaseFactory::getInstance()->query($sql)->fetchAll());

if ($venuesCount == 0) {
	redirect('account.php', 'There are 0 venues. Create a venue first.');
}

$h = new FormHandler('FormEventCreate');
$f = new FormEventCreate();

if ($f->validate()) {
	$f->process();

	redirect('listEvents.php', 'Event created');
}

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

$tpl->displayForm($f);

require_once 'includes/widgets/footer.php';

?>
