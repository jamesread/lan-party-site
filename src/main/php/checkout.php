<?php

require_once 'includes/common.php';
require_once 'includes/classes/Basket.php';
require_once 'includes/classes/Events.php';

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

	logActivity('Selected BACS at checkout.');

	echo '<div class = "box">';
	echo getContent('bacs');
	echo '</div>';

	echo '<div class = "box">When you have done this, please <a href = "checkout.php?action=bacsComplete">click here to Confirm BACS payment</a></div>';

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
	logAndRedirect('account.php', 'Paypal transaction failed.');
	break;
case 'paypalComplete':
	logActivity('Started processing PayPal payment notification');

	foreach (Basket::getContents() as $ticket) {
		logActivity('PayPal transaction processing - setting status to PAID for event. Ticket owner _u_, event _e_', $ticket['userId'], array('event' => $ticket['eventId'], 'user' => Session::getUser()->getId()));

		Events::setSignupStatus($ticket['userId'], $ticket['eventId'], 'PAID', false);
	}

	logActivity('Finished processing PayPal payment notification.');

	Basket::clear();

	redirect('account.php', 'Thanks, payment complete!');

	break;
default:
	require_once 'includes/widgets/header.php';
	require_once 'includes/widgets/sidebar.php';

	startBox();

	echo str_replace('%BASKETTOTAL%', doubleToGbp($cost), getContent('selectPaymentMethod'));
	
	logActivity('Went to the checkout (but not clicked on anything yet), with ' . doubleToGbp($cost) . ' of stuff in the basket.');

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
