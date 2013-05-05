<?php
date_default_timezone_set('Europe/London');
ini_set('display_errors', 'on');

define('CFG_DB_DSN', 'mysql:dbname=lps');
define('CFG_DB_USER', 'root');
define('CFG_DB_PASS', '');
//define('CFG_DIR_TEMPLATE_CACHE', '/var/cache/httpd/lps/');

// The following is configuration for advanced users only.
define('CFG_PASSWORD_SALT', '4fd0e4a8c287f'); // If you change this value, you will break all existing user passwords.  
?>
