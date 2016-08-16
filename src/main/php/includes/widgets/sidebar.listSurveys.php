<?php

use \libAllure\HtmlLinksCollection;
use \libAllure\Session;

$menu = new HtmlLinksCollection('Survey admin');
$menu->addIf(Session::hasPriv('SURVEY_CREATE'), 'createSurvey.php', 'Create');

if (isset($survey['id'])) {
	$menu->addIf(Session::hasPriv('SURVEY_UPDATE'), 'updateSurvey.php?id=' . $survey['id'], 'Update');
	$menu->addIf(Session::hasPriv('SURVEY_UPDATE'), 'viewSurveyVotes.php?id=' . $survey['id'], 'Detailed votes');
}

if ($menu->hasLinks()) {
	$tpl->assign('links', $menu);
	$tpl->display('sidebarLinks.tpl');
}


?>
