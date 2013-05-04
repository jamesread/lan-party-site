<?php

require_once 'includes/common.php';
require_once 'includes/classes/FormSendEmail.php';

use \libAllure\Session;
use \libAllure\Sanitizer;
use \libAllure\User;

Session::requirePriv('SENDEMAIL');

$userId = Sanitizer::getInstance()->filterUint('userId');
$user = User::getUserById($userId);
$email = $user->getData('email');

if (empty($email)) {
	redirect('account.php', 'Cannot send email to a user with a blank email address.');
}

$f = new FormSendEmail($email);
$f->addElementHidden('userId', $userId);

if ($f->validate()) {
	$f->process();

	redirect('profile.php?id=' . $userId, 'Your contribution to the spam on the internet has been completed.');
} else {
	require_once 'includes/widgets/header.php';

	$tpl->assignForm($f);
	$tpl->display('form.tpl');
}

require_once 'includes/widgets/footer.php';



?>
