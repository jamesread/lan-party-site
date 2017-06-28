<?php

function add_include_path($path) {
	set_include_path(get_include_path() . PATH_SEPARATOR . $path);
}

date_default_timezone_set('Europe/London');

@include 'includes/bootstrap.php';

require_once dirname(__FILE__) . '/libraries/autoload.php';

use \libAllure\IncludePath;

IncludePath::add('/../');
IncludePath::add('/classes/');
IncludePath::add_libAllure();

if (!interface_exists('\JsonSerializable')) {
	interface JsonSerializable {}
}

require_once 'includes/classes/Plugin.php';
require_once 'includes/classes/SessionBasedNotifications.php';
require_once 'includes/functions.php';

if (getSiteSetting('forceHttps')) {
	redirectHttpToHttps();
}

\libAllure\Form::$fullyQualifiedElementNames = false;
//\libAllure\ElementDate::$js = "<script type = \"text/javascript\">$('#NAME').datetimepicker({ dateFormat: 'yy-mm-dd', firstDay: 1, hour: 19, changeYear: true, changeMonth: true }); </script>";
\libAllure\ErrorHandler::getInstance()->beGreedy();

require_once 'libAllure/Template.php';

$tpl = new \libAllure\Template((defined('CFG_DIR_TEMPLATE_CACHE') ? CFG_DIR_TEMPLATE_CACHE : 'lps'));
$tpl->addAutoClearVar('excludeBox');
$tpl->registerFunction('hasPriv', '\libAllure\Session::hasPriv');
$tpl->registerFunction('getContent', 'tplGetContent');

if ((@include 'includes/config.php') !== false) {
	require_once 'includes/config.php';
	$db = new \libAllure\Database(CFG_DB_DSN, CFG_DB_USER, CFG_DB_PASS);

	\libAllure\DatabaseFactory::registerInstance($db);

	require_once 'libAllure/AuthBackend.php';
	require_once 'libAllure/AuthBackendDatabase.php';

	$backend = new \libAllure\AuthBackendDatabase();
	$backend->setSalt(null, CFG_PASSWORD_SALT);
	$backend->registerAsDefault();

	\libAllure\Session::$cookieDomain = getSiteSetting('cookieDomain');
	\libAllure\Session::setSessionName('westlanUser');
	\libAllure\Session::setCookieLifetimeInSeconds(604800);
	\libAllure\Session::start();

	$tpl->template_dir = getThemeDirectory() . '/templates';
} else if (!defined('INSTALLATION_IN_PROGRESS')) {
	redirect('installer.php', 'No config file found, assuming installation.');
}

?>
