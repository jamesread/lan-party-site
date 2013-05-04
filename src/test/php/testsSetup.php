<?php

set_include_path(get_include_path() . PATH_SEPARATOR . 'src/main/php/' . PATH_SEPARATOR . '/usr/share/php/');
date_default_timezone_set('Europe/London');

function __autoload($class) {
	$class = DIRECTORY_SEPARATOR . $class . '.php';

	foreach (explode(PATH_SEPARATOR, get_include_path()) as $path) {
		if (file_exists($path . $class)) {
			require_once $path . $class;
			return;
		}
	}
}

require_once 'includes/functions.php';

require_once 'libAllure/Exceptions.php';
require_once 'libAllure/ErrorHandler.php';
require_once 'libAllure/Database.php';
require_once 'libAllure/Form.php';
require_once 'libAllure/Logger.php';
require_once 'libAllure/User.php';
require_once 'libAllure/Inflector.php';
require_once 'libAllure/Session.php';
require_once 'libAllure/AuthBackend.php';
require_once 'libAllure/AuthBackendDatabase.php';
require_once 'libAllure/HtmlLinksCollection.php';

require_once 'config.php';

\libAllure\ErrorHandler::getInstance()->beGreedy();

$db = new \libAllure\Database(CFG_DB_DSN, CFG_DB_USER, CFG_DB_PASS);
\libAllure\DatabaseFactory::registerInstance($db);

\libAllure\AuthBackend::setBackend(new \libAllure\AuthBackendDatabase());
\libAllure\Session::checkCredentials('SYSTEM', '');

?>
