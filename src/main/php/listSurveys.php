<?php

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

startbox();
$sql = 'SELECT id, title, active FROM surveys WHERE active = 1 ';
$result = $db->query($sql);

$tpl->assign('listSurveys', $result->fetchAll());
$tpl->display('listSurveys.tpl');

require_once 'includes/widgets/footer.php';

?>
