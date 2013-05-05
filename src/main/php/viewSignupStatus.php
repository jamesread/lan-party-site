<?php

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

$sql = 'SELECT e.name AS event_name, e.date AS event_date, e.id AS event_id, s.status, count(s.status) as count FROM signups s, events e WHERE s.event = e.id GROUP BY s.status ORDER BY e.date DESC';
$sql = 'SELECT e.name, e.id AS event_id, e.name AS event_name, k.status, count(k.status) AS count, e.date FROM events e, (SELECT s.id, s.status, s.event FROM signups s) AS k WHERE k.event = e.id GROUP BY e.id, k.status ORDER BY e.id ASC, k.status ASC';
$signupStats = $db->query($sql);

$tpl->assign('signupStats', $signupStats);
$tpl->display('signupStats.tpl');

require_once 'includes/widgets/footer.php';

?>
