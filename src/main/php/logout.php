<?php

require_once 'includes/common.php';

use \libAllure\Session;

if (Session::isLoggedIn()) {
	Session::logout();
}

redirect('index.php', 'You have been logged out.');

?>
