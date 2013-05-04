<?php

require_once 'includes/common.php';
require_once 'includes/classes/FormTargetedMailingList.php';

requirePrivOrRedirect('VIEW_MAILING_LIST');

$f = new FormTargetedMailingList();


require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

if ($f->validate()) {
	$eventList = $f->getElementvalue('eventList');

	$tpl->assign('isEventSpecific', true);

	if ($f->getElementValue('ignoreOptOut')) {
		$sql = 'SELECT u.email, e.name AS eventName, e.id AS eventId FROM users u, signups s, events e WHERE s.user = u.id AND u.emailFlagged = 0 AND s.status IS NOT NULL AND s.event = e.id AND e.id = :event ';
	} else {
		$sql = 'SELECT u.email, e.name AS eventName, e.id AS eventId FROM users u, signups s, events e WHERE s.user = u.id AND u.emailFlagged = 0 AND s.status IS NOT NULL AND s.event = e.id AND e.id = :event AND u.mailingList = 1';
	}

	$stmt = $db->prepare($sql);
	$stmt->bindValue(':event', $eventList);
	$stmt->execute();

} else {
	$tpl->assign('isEventSpecific', false);

	$sql = 'SELECT u.email FROM users u WHERE u.mailingList = 1 AND u.email IS NOT NULL AND u.emailFlagged = 0';
	$stmt = $db->query($sql);
}

$addresses = array();
foreach ($stmt->fetchAll() as $user) {
	$addresses[] = $user['email'];
}

$tpl->assignForm($f);
$tpl->assign('mailingListRecipients', $addresses);
$tpl->display('mailingList.tpl');

require_once 'includes/widgets/footer.php';

?>
