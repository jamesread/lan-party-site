<?php

header('Access-Control-Allow-Origin: *');

require_once '../../includes/common.php';

function outputJson($out) {
	header('Content-Type: application/json');
	echo json_encode($out);
}

?>
