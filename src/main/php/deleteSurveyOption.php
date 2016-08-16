<?php

require_once 'includes/common.php';

use \libAllure\Session;

if (!Session::hasPriv('SURVEY_OPTION_DELETE')) {
	throw new PermissionsException();
}

$sql = 'DELETE FROM survey_options WHERE id = :id ';
$stmt = $db->prepare($sql);
$stmt->bindValue(':id', $_REQUEST['id']);
$stmt->execute();

redirect('viewSurvey.php?id=' . $_REQUEST['surveyId'], 'Survey option deleted.');

?>
