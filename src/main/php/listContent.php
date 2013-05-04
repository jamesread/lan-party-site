<?php

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

use \libAllure\DatabaseFactory;

$sql = 'SELECT pc.id, pc.page, pc.updated, pc.updatedBy AS user, u.username, g.css AS userGroupCss FROM page_content AS pc LEFT JOIN users u ON pc.updatedBy = u.id LEFT JOIN groups g ON u.group = g.id ORDER BY pc.updated DESC ';
$stmt = DatabaseFactory::getInstance()->prepare($sql);
$stmt->execute();

$tpl->assign('listContent', $stmt->fetchAll());
$tpl->display('listContent.tpl');

require_once 'includes/widgets/footer.php';

?>
