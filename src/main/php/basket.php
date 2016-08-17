<?php

require_once 'includes/common.php';
require_once 'includes/classes/Events.php';
require_once 'includes/classes/FormPayForFriend.php';
require_once 'includes/classes/FormAddToBasket.php';
require_once 'includes/classes/Basket.php';

use \libAllure\Session;
use \libAllure\Sanitizer;
use \libAllure\DatabaseFactory;

if (!Session::isLoggedIn()) {
	redirect('login.php', 'You need to <a href = "login.php">login</a> or <a href = "register.php">register</a> to pay for events.');
}

$sanitizer = new Sanitizer();
$action = $sanitizer->filterString('action');

if (isset($_REQUEST['event'])) {
	$eventId = intval($_REQUEST['event']);
	$event = Events::getById($eventId);

	switch ($action) {
	case 'addPersonal':
		Basket::addEvent($event);
		redirect('basket.php', 'Ticked added', false, 1);
	case 'delete';
		Basket::removeEvent($event, $_REQUEST['user']);

		redirect('basket.php', 'Ticket removed', false, -1);
	}
}

$signupableEvents = Events::getSignupableEvents();
$payableEvents = Events::getPayableEvents();

$tpl->assign('signupableEvents', $signupableEvents);

$formAddToBasket = new FormAddToBasket($payableEvents);

if ($formAddToBasket->validate()) {
	$formAddToBasket->process();

	redirect('basket.php', 'Ticket added to basket');
}

$tpl->assign('addToBasketHasEvents', $formAddToBasket->hasEvents);
$tpl->assignForm($formAddToBasket, 'addToBasket');

$formPayForFriend = new FormPayForFriend($signupableEvents);

if ($formPayForFriend->validate()) {
	$formPayForFriend->process();

	redirect('basket.php', 'Ticked added for friend.');
}

$tpl->assignForm($formPayForFriend, 'payForFriend');

$tpl->assign('basketItems', Basket::getContents());
$tpl->assign('basketTotal', Basket::getTotal());
$tpl->assign('basketIsEmpty', Basket::isEmpty());

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

$tpl->display('basket.tpl');

require_once 'includes/widgets/footer.php';

?>
