<?php

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

requirePrivOrRedirect('LIST_FINANCE_ACCOUNTS');

use \libAllure\DatabaseFactory;

$sql = 'SELECT a.id, a.title, u.id AS managerId, u.username AS managerUsername, sum(t.amount) AS amount FROM finance_accounts a LEFT JOIN finance_transactions t ON t.account = a.id LEFT JOIN users u ON a.assigned_to = u.id GROUP BY a.id ORDER BY a.title ASC';
$stmt = DatabaseFactory::getInstance()->prepare($sql);
$stmt->execute();

$tpl->assign('listAccounts', $stmt->fetchAll());
$tpl->display('listFinancialAccounts.tpl');

require_once 'includes/widgets/footer.php';

?>
