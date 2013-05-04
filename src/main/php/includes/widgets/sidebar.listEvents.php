<?php

use \libAllure\HtmlLinksCollection;
use \libAllure\Session;

$eventAdminLinks = new HtmlLinksCollection('Events admin');
$eventAdminLinks->addIf(Session::hasPriv('EVENT_CREATE'), 'createEvent.php', 'Create event', null, 'create');
$eventAdminLinks->addIf(Session::hasPriv('EVENT_VIEW_SIGNUP_STATS'), 'viewSignupStatus.php', 'Signup status');

if ($eventAdminLinks->hasLinks()) {
	$tpl->assign('linksCollection', $eventAdminLinks);
	$tpl->display('sidebarWidgetLinks.tpl');
}

?>
