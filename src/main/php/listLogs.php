<?php

require_once 'includes/common.php';
require_once 'includes/widgets/header.php';

requirePrivOrRedirect('LIST_LOGS');

$sql = 'SELECT l.id, u.id AS user_id, u.username, l.date, l.message, l.ipAddress, g.css AS userGroupCss FROM log l LEFT JOIN users u on l.user = u.id JOIN `groups` g ON u.`group` = g.id ORDER BY id DESC LIMIT 50';
$result = $db->query($sql);

$logs = $result->fetchAll();
$tpl->assign('listLogs', $logs);
$tpl->display('listLogs.tpl');

require_once 'includes/widgets/footer.php';

?>
