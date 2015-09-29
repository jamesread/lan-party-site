<?php

require_once 'includes/common.php';

use \libAllure\Session;

if (!Session::isLoggedIn()) {
	redirect('login.php', 'You need to be logged in!');
}

require_once 'includes/widgets/header.php';

applyAchievements();

$sql = 'SELECT a.id, a.title, a.description FROM achievements a';
$stmt = $db->prepare($sql);
$stmt->execute();
$avail = $stmt->fetchAll();

$earned = getAcheivements();
$earnedIds = array();

foreach ($earned as $acheiv) {
	$earnedIds[] = $acheiv['id'];
}

for ($i = 0; $i < sizeof($avail); $i++) {
	$avail[$i]['earned'] = in_array($avail[$i]['id'], $earnedIds);
}

applyAcheivIcons($avail);

$tpl->assign('listAchievements', $avail);
$tpl->display('listAchievements.tpl');

require_once 'includes/widgets/footer.php';
?>
