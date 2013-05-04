<?php

if (defined('REDIRECT') && defined('REDIRECT_TIMEOUT') && REDIRECT_TIMEOUT == 0) {
	header('HTTP/1.1 303 See Other');
	header('Location:' . REDIRECT);
}

require_once 'includes/common.php';

global $tpl;

if (defined('REDIRECT')) {
	$tpl->assign('redirect', REDIRECT);
	$tpl->assign('redirectTimeout', REDIRECT_TIMEOUT);
}

$tpl->assign('siteTitle', getSiteSetting('siteTitle', 'Untitled LPS site'));
$tpl->assign('theme', getSiteSetting('theme', 'airdale'));

$tpl->display('header.minimal.tpl');

?>
