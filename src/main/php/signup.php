<?php

require_once 'includes/common.php';
require_once 'includes/classes/Events.php';
require_once 'includes/classes/FormSignup.php';

use \libAllure\Session;

if (!Session::isLoggedIn()) {
	redirect('index.php', 'Ah, you are not logged in. How did you even get here? I am confused.');
}

$event = Events::getById(intval($_REQUEST['event']));

if (isset($_REQUEST['user']) && $_REQUEST['user'] != Session::getUser()->getID()) {
		if (Session::hasPriv('SIGNUPS_MODIFY')) {
			$user = intval($_REQUEST['user']);
		} else {
			throw new PermissionsException();
		}
} else {
	$user = Session::getUser()->getId();
}

$f = new FormSignup($event, $user, 'SIGNEDUP');

if ($f->validate()) {
	$f->process();

	logActivity('Signed up to event: ' . $event['name'], Session::getUser()->getId());
	redirect('viewEvent.php?id=' . $event['id'], 'You have been signed up.');
}

//if (isset($_REQUEST['status'])) {
//	Events::setSignupStatus($user, $event['id'], $_REQUEST['status']);
//
//	redirect('viewEvent.php?id=' . $event['id'], 'Erm, thanks.');
//}

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

$tpl->displayForm($f);

require_once 'includes/widgets/footer.php';

?>
