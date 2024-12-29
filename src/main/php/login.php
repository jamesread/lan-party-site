<?php

require_once 'includes/common.php';
require_once 'libAllure/Form.php';
require_once 'libAllure/Session.php';

use \libAllure\Logger;

require_once 'includes/classes/FormLogin.php';

$loginForm = new FormLogin();

use \libAllure\Session;

// In case of redirect...
ob_start(); 
global $db;


if (Session::isLoggedIn()) {
	if (isset($_REQUEST['redirect'])) {
		redirect($_REQUEST['redirect'], 'You are being redirected, hang tight!');
	} else {
		redirect('index.php', 'You are already logged in!');
	}

	require_once 'includes/widgets/footer.php';
}


if (isset($_REQUEST['username'])) {
	$loginForm->getElement('username')->setValue(filter_var($_REQUEST['username'], FILTER_DEFAULT));
}

if ($loginForm->validate()) {
	$username = $loginForm->getElementValue('username');
	$password = $loginForm->getElementValue('password');

	try {
		Session::checkCredentials($username, $password);
		applyAchievements();

		logActivity('Logged in');
		redirect('index.php', 'You have sucessfully logged in.');
	} catch (\libAllure\UserNotFoundException $e) {
		$loginForm->setElementError('username', 'User not found.');
	} catch (\libAllure\IncorrectPasswordException $e) {
		$loginForm->setElementError('password', 'Incorrect password.');
	} catch (Exception $e) {
            var_dump($e);
		$loginForm->getElement('username')->setValidationError('Failed to login because of a system problem.');
		Logger::messageException($e);
	}
}

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

ob_end_flush();

if (isset($_REQUEST['redirect'])) {
	echo '<p><strong>You will be redirected after you login.</strong></p>';

	$loginForm->addElement(new ElementHidden('redirect', $_REQUEST['redirect']));
}

$tpl->assign('isMaintMode', getSiteSetting('maintenanceMode'));
$tpl->assignForm($loginForm);
$tpl->display('login.tpl');

require_once 'includes/widgets/footer.php';

?>
