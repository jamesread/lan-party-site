<?php

require_once 'includes/common.php';

use \libAllure\ErrorHandler;

$inContent = in_array('includes/widgets/header.php', get_included_files());

if ($inContent) {
	require_once 'includes/widgets/header.php';
}

$error = (isset($_GET['error'])) ? intval($_GET['error']) : null;

switch ($error) {
case '404': ErrorHandler::getInstance()->handleHttpError(404); break;
case '403': ErrorHandler::getInstance()->handleHttpError(403); break;
default:
	throw new Exception('An unknown error has occoured.');
}

require_once 'includes/widgets/footer.php';

?>
