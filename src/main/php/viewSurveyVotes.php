<?php

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

$id = intval($_REQUEST['id']);

// Fetch the survey.
$sql = 'SELECT id, title, count FROM surveys WHERE id = :id LIMIT 1';
$stmt = $db->prepare($sql);
$stmt->bindValue(':id', $id);
$stmt->execute();

if ($stmt->numRows() != 1) {
	throw new Exception('Survey not found.');
}

$survey = $stmt->fetchRow();

$stmt = $db->prepare('SELECT u.username, group_concat(so.value) AS value FROM survey_options so LEFT JOIN (surveys s) ON s.id = so.survey LEFT JOIN (survey_votes sv) ON so.id = sv.opt LEFT JOIN (users u) ON sv.user = u.id WHERE s.id = :surveyId GROUP BY u.id');
$stmt->bindValue(':surveyId', $id);
$stmt->execute();

$tpl->assign('survey', $survey);
$tpl->assign('listOptions', $stmt->fetchAll());
$tpl->display('viewSurveyVotes.tpl');

require_once 'includes/widgets/footer.php';

?>
