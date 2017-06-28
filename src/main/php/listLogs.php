<?php

require_once 'includes/common.php';
require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

requirePrivOrRedirect('LIST_LOGS');

$sql = 'SELECT l.id, u.id AS user_id, u.username, l.date, l.message, l.ipAddress, g.css AS userGroupCss, l.associatedUser, au.username AS associatedUsername, l.associatedEvent, ae.name AS associatedEventName FROM log l LEFT JOIN users u on l.user = u.id JOIN `groups` g ON u.`group` = g.id LEFT JOIN users au ON l.associatedUser = au.id LEFT JOIN events ae ON l.associatedEvent = ae.id ORDER BY id DESC LIMIT 250';
$result = $db->query($sql);

$logs = $result->fetchAll();

for ($i = 0; $i < sizeof($logs); $i++) {
	$logs[$i]['message'] = str_replace('_u_', '<a href = "profile.php?id=' . $logs[$i]['associatedUser'] . '">'. $logs[$i]['associatedUsername'] .'</a>', $logs[$i]['message']);
	$logs[$i]['message'] = str_replace('_e_', '<a href = "viewEvent.php?id=' . $logs[$i]['associatedEvent'] . '">'. $logs[$i]['associatedEventName'] .'</a>', $logs[$i]['message']);
}

$tpl->assign('listLogs', $logs);
$tpl->display('listLogs.tpl');

require_once 'includes/widgets/footer.php';

?>
