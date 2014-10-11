<?php

require_once 'includes/common.php';

use \libAllure\DatabaseFactory;
use \libAllure\Database;

$sql = 'SELECT id, title, active, count FROM surveys WHERE id = :id ';
$stmt = $db->prepare($sql);
$stmt->bindValue(':id', $_REQUEST['id'], Database::PARAM_INT);
$stmt->execute();

if ($stmt->numRows() == 0) {
	throw new Exception('Survey not found.');
}

$survey = $stmt->fetchRow();

$formSurveyEdit = new FormSurveyEdit($survey);
$formAddOption = new FormSurveyAddOption($survey);

if ($formSurveyEdit->validate()) {
	$formSurveyEdit->process();

	redirect('updateSurvey.php?id=' . $survey['id'], 'Survey edited.');
} else if ($formAddOption->validate()) {
	$formAddOption->process();

	redirect('updateSurvey.php?id=' . $survey['id'], 'Survey options updated.');
}

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

$tpl->displayForm($formSurveyEdit);

$tpl->displayForm($formAddOption);

$tpl->assign('title', 'Finished editing?');
$tpl->assign('message', 'If you have finished editing, <a href = "viewSurvey.php?id=' . $survey['id'] . '">return to the survey</a>.');
$tpl->display('notification.tpl');

require_once 'includes/widgets/footer.php';

?>

