<?php

set_include_path(get_include_path() . PATH_SEPARATOR . (dirname(__FILE__) . '/../') . PATH_SEPARATOR . 'includes/classes/');
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

require_once 'libAllure/Exceptions.php';
require_once 'libAllure/ErrorHandler.php';
require_once 'libAllure/Database.php';
require_once 'libAllure/Form.php';
require_once 'libAllure/Logger.php';
require_once 'libAllure/User.php';
require_once 'libAllure/Inflector.php';
require_once 'libAllure/Session.php';
require_once 'libAllure/HtmlLinksCollection.php';
require_once 'libAllure/Sanitizer.php';
require_once 'libAllure/FormHandler.php';
require_once 'includes/classes/Plugin.php';
require_once 'includes/classes/SessionBasedNotifications.php';
require_once 'includes/functions.php';

\libAllure\Form::$fullyQualifiedElementNames = false;
\libAllure\ErrorHandler::getInstance()->beGreedy();

require_once 'libAllure/Template.php';

$tpl = new \libAllure\Template((defined('CFG_DIR_TEMPLATE_CACHE') ? CFG_DIR_TEMPLATE_CACHE : 'lps'));
$tpl->addAutoClearVar('excludeBox');
$tpl->registerFunction('hasPriv', '\libAllure\Session::hasPriv');
$tpl->registerFunction('getContent', 'tplGetContent');

if ((@include 'includes/config.php') !== false) {
	require_once 'includes/config.php';
	$db = connectDatabase();

	require_once 'libAllure/AuthBackend.php';
	require_once 'libAllure/AuthBackendDatabase.php';

	$backend = new \libAllure\AuthBackendDatabase();
	$backend->setSalt(null, CFG_PASSWORD_SALT);
	$backend->registerAsDefault();

	\libAllure\Session::$cookieDomain = getSiteSetting('cookieDomain');
	\libAllure\Session::setSessionName('lpsUser');
	\libAllure\Session::setCookieLifetimeInSeconds(604800);
	\libAllure\Session::start();

} else if (!defined('INSTALLATION_IN_PROGRESS')) {
	redirect('installer.php', 'No config file found, assuming installation.');
}

?>
