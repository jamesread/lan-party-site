<?php

date_default_timezone_set('Europe/London');
define('LPS_ROOT', dirname(dirname(__FILE__)) . '/');

@include 'bootstrap.php';

$loader = @include_once LPS_ROOT . 'includes/libraries/autoload.php';

if (!$loader) {
    die("Could not load the autoloader! This probably means you don't have any composer libraries installed. Run a `composer update`.");
}

$loader->addPsr4('', LPS_ROOT . 'includes/classes/');

use \libAllure\IncludePath;
IncludePath::add(LPS_ROOT);
IncludePath::addLibAllure();

require_once 'includes/functions.php';

if (getSiteSetting('forceHttps')) {
    redirectHttpToHttps();
}

\libAllure\Form::$fullyQualifiedElementNames = false;
//\libAllure\ElementDate::$js = "<script type = \"text/javascript\">$('#NAME').datetimepicker({ dateFormat: 'yy-mm-dd', firstDay: 1, hour: 19, changeYear: true, changeMonth: true }); </script>";
\libAllure\ErrorHandler::getInstance()->beGreedy();

require_once 'libAllure/Template.php';

$tpl = new \libAllure\Template((defined('CFG_DIR_TEMPLATE_CACHE') ? CFG_DIR_TEMPLATE_CACHE : 'lps'));
$tpl->template_dir = getThemeDirectory() . '/templates';
$tpl->addAutoClearVar('excludeBox');
$tpl->registerFunction('hasPriv', '\libAllure\Session::hasPriv');
$tpl->registerFunction('getContent', 'tplGetContent');
$tpl->registerFunction('formatDt', 'formatDt');
$tpl->registerPlugin('modifier', 'doubleToGbp', 'doubleToGbp');

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
