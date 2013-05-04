<?php

define('PWD', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('CFG_FILE_PATH', PWD . DIRECTORY_SEPARATOR . 'config.php');
define('COMMON_FILE_LOCATION', PWD . DIRECTORY_SEPARATOR . 'htdocs/includes/common.php');



class SystemChecks {
	public function runTest ($result, $description, $failOkay = false) {
		if ($result) {
			$result = '<span style = "font-weight: bold; color: green;">Pass</span>';
		} else {
			if ($failOkay) {
				$result = '<span style = "font-weight:bold; color:orange;">Warning</span>';
			} else {
				$result = '<span style = "font-weight:bold; color:red;">Fail</span>';
			}
		}


		echo '<tr><td>' . $description . '</td><td>' . $result . '</td></tr>';
	}

	public function testBasic() {
runTest(1, 'boolean sanity');
runTest(file_exists(COMMON_FILE_LOCATION), '/includes/common.php exists');
require_once COMMON_FILE_LOCATION;
runTest(class_exists('Database'), 'Database class exists');
runTest(class_exists('ErrorHandler'), 'ErrorHandler class exists');
runTest(intval(ini_get('register_globals')) == '0', 'Register globals turned off');
runTest(get_magic_quotes_gpc() === 0, 'Magic quotes turned off');
runTest(version_compare(PHP_VERSION, '5.0.0', '>'), 'PHP Version is reasonable');
runTest(file_exists(CFG_FILE_PATH), 'Config file exists');
require_once CFG_FILE_PATH;
runTest(!is_writable(CFG_FILE_PATH), 'Config file is not writable from webserver', true);
runTest(defined('CFG_DB_DSN'), 'Defined: DB_DSN');
runTest(defined('CFG_DB_USER'), 'Defined: DB_USER');
runTest(defined('CFG_DB_PASS'), 'Defined: DB_PASS');
runTest(defined('CFG_PASSWORD_SALT'), 'Defined: password salt');
runTest(strlen(CFG_PASSWORD_SALT) >= 15, 'Password salt length');
runTest(file_exists(PWD . 'htdocs'), 'htdocs exists');
runTest(function_exists('mysql_connect'), 'mysql_ functions are installed');
runTest(function_exists('mysqli_connect'), 'mysqli_ functions are installed', true);
runTest(class_exists('PDO'), 'PDO exists');
	}
}

?>
