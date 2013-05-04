<?php

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';
require_once 'libAllure/Sanitizer.php';

use \libAllure\DatabaseFactory;
use \libAllure\Sanitizer;

$sanitizer = new Sanitizer();

$sql = 'SELECT a.id, a.title FROM finance_accounts a WHERE a.id = :id ';
$stmt = DatabaseFactory::getInstance()->prepare($sql);
$stmt->bindValue(':id', $sanitizer->filterUint('id'));
$stmt->execute();

$tpl->assign('account', $stmt->fetchRow());

$sql = 'SELECT t.id, t.amount, t.description, t.timestamp FROM finance_transactions t WHERE t.account = :accountId';
$stmt = DatabaseFactory::getInstance()->prepare($sql);
$stmt->bindValue('accountId', $sanitizer->filterUint('id'));
$stmt->execute();

$tpl->assign('listTransactions', $stmt->fetchAll());

$tpl->display('viewFinanceAccount.tpl');

require_once 'includes/widgets/footer.php';

?>
