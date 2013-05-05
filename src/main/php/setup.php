<?php

require_once 'includes/widgets/header.php';

use \libAllure\DatabaseFactory;
use \libAllure\AuthBackend;
use \libAllure\HtmlLinksCollection;

$db = DatabaseFactory::getInstance();

$sql = 'SELECT count(u.id) AS count FROM users u';
$stmt = $db->prepare($sql);
$stmt->execute();
$countUsers = $stmt->fetchRow();
$countUsers = $countUsers['count'];

$setupLinks = new HtmlLinksCollection();
if ($countUsers == 1 || isset($_REQUEST['recreate'])) {
    $sql = 'DELETE FROM users WHERE username = "admin"';
	$stmt = $db->prepare($sql)->execute();
	$adminPassword = uniqid();

	$sql = 'INSERT INTO users (username, password, `group`) VALUES (:username, :password, 1)';
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':username', 'admin');
	$stmt->bindValue(':password', AuthBackend::getInstance()->hashPassword($adminPassword));
	$stmt->execute();

    $tpl->assign('message', 'User account created. Your username is <strong>admin</strong> and your password is <strong>' . $adminPassword . '</strong>');
	$setupLinks->add('login.php', 'Login');
} else {

	$tpl->assign('message', 'Admin account already exists.');
	$setupLinks->add('login.php', 'Login');
	$setupLinks->add('?recreate', 'Recreate');
}

$tpl->assign('links', $setupLinks);
$tpl->display('notification.tpl');


require_once 'includes/widgets/footer.php';

?>
