<?php

require_once 'includes/widgets/header.php';

function buildStatNumeric($title, $value) {
	return buildStat($title, $value);
}

function buildStat($title, $value) {
	return array (
		'title' => $title,
		'value' => $value,
	);
}

function bytesToHuman($bytes) {
    $types = array( 'B', 'KB', 'MB', 'GB', 'TB' );
    for ($i = 0; $bytes >= 1024 && $i < ( count( $types ) -1 ); $bytes /= 1024, $i++ );
    return( round( $bytes, 2 ) . " " . $types[$i] );
}


$stats[] = buildStatNumeric('diskspace.free', bytesToHuman(disk_free_space('resources/images/')), 100, 20);
$stats[] = buildStat('php.version', PHP_VERSION);

$tpl->assign('listStatistics', $stats);
$tpl->display('listSystemStatistics.tpl');

require_once 'includes/widgets/footer.php';

?>
