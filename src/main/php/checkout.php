<?php

require_once 'includes/common.php';
require_once 'includes/classes/Basket.php';
require_once 'includes/classes/Events.php';
require_once 'includes/classes/FormPayTicketCash.php';

use \libAllure\Session;
use \libAllure\Sanitizer;

if (!Session::isLoggedIn()) {
	redirect('login.php', 'You must login to use the checkout!');
}

if (Basket::isEmpty()) {
	redirect('basket.php', 'You cannot go to the checkout with an empty basket!');
}

$sanitizer = new Sanitizer();

$cost = Basket::getTotal();

switch ($sanitizer->filterString('action')) {
case 'cash':
	$f = new FormPayTicketCash();

	if ($f->validate()) {
		$f->process();

		foreach (Basket::getContents() as $ticket) {
			Events::setSignupStatus(Session::getUser()->getId(), $ticket['eventId'], 'CASH_IN_POST');
		}

		Basket::clear();

		redirect('account.php', 'Thanks, you will be marked as PAID by an admin when they receive the cash.');
	}

	require_once 'includes/widgets/header.php';

	$f->addElementHidden('action', 'cash');
	$tpl->assignForm($f);
	$tpl->display('form.tpl');

	require_once 'includes/widgets/footer.php';

	break;
case 'bacs':
	require_once 'includes/widgets/header.php';

	$tpl->display('checkout.bacs.tpl');

	require_once 'includes/widgets/footer.php';

	break;
case 'bacsComplete':
	foreach (Basket::getContents() as $ticket) {
		Events::setSignupStatus(Session::getUser()->getId(), $ticket['eventId'], 'BACS_WAITING');
	}

	Basket::clear();

	redirect('account.php', 'Thanks, you will be marked as PAID by an admin when they receive the transfer.');

	break;
case 'paypalFail':
	redirect('account.php', 'Paypal transaction failed.');
	break;
case 'paypalComplete':
	foreach (Basket::getContents() as $ticket) {
		Events::setSignupStatus(Session::getUser()->getId(), $ticket['eventId'], 'PAYPAL_WAITING');
	}

	Basket::clear();

	redirect('account.php', 'Thanks, you will be marked as PAID by an admin when they receive the transfer from paypal.');

	break;
default:
	require_once 'includes/widgets/header.php';
	require_once 'includes/widgets/sidebar.php';

	startBox();

	echo str_replace('%BASKETTOTAL%', doubleToGbp($cost), getContent('selectPaymentMethod'));

	$tpl->assign('cost', $cost);
	$tpl->assign('costPaypal', getPaypalCommission($cost));
	$tpl->assign('paypalEmail', getSiteSetting('paypalEmail'));
	$tpl->assign('listBasketContents', Basket::getContents());
	$tpl->assign('baseUrl', getSiteSetting('baseUrl'));
	$tpl->assign('currency', getSiteSetting('currency'));
	$tpl->display('checkout.tpl');

	echo getContent('commissionDisclaimer');

	stopBox('Checkout');
}

box('If you are not yet finished, pop back to your <a href = "basket.php">basket</a>.', 'Agh, no!');

require_once 'includes/widgets/footer.php';

?>
