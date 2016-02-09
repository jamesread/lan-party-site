<?php

require_once 'includes/common.php';

use \libAllure\Session;
use \libAllure\Sanitizer;

$id = Sanitizer::getInstance()->filterUint('id');

// Fetch the survey.
$sql = 'SELECT id, title, count FROM surveys WHERE id = :id LIMIT 1';
$stmt = $db->prepare($sql);
$stmt->bindValue(':id', $id);
$stmt->execute();

if ($stmt->numRows() != 1) {
	throw new Exception('Survey not found.');
}

$survey = $stmt->fetchRow();

// Fetch the optiosn for this survey.
$sql = 'SELECT id, value, 0 as voteCount FROM survey_options WHERE survey = :id';
$stmt = $db->prepare($sql);
$stmt->bindValue(':id', $survey['id']);
$stmt->execute();

$listOptions = $stmt->fetchAll();

if (count($listOptions) == 0) {
	require_once 'includes/widgets/header.php';
	require_once 'includes/widgets/sidebar.php';
	$tpl->error('This survey has no options yet.');
} else {
	$options = array();
	foreach ($listOptions as $option) {
		$options[$option['id']] = $option;
	}

	$f = new FormSurveyVote($survey, $options);

	if ($f->validate()) {
		$f->process();

		redirect('viewSurvey.php?id=' . $survey['id'], 'Thanks for voting.');
	}

	require_once 'includes/widgets/header.php';
	require_once 'includes/widgets/sidebar.php';

	// Fetch the votes for the survey.
	$sql = 'SELECT sv.id, sv.user, u.username, so.id optionId FROM survey_votes sv, survey_options so, users u WHERE sv.user = u.id AND sv.opt = so.id AND so.survey = :id';
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':id', $survey['id']);
	$stmt->execute();
	$totalVotes = $stmt->numRows();

	$tpl->assign('totalVotes');

	foreach ($stmt->fetchAll() as $vote) {
		$options[$vote['optionId']]['voteCount']++;
	}

	foreach ($options as &$option) {
		if ($option['voteCount'] == 0) {
			$option['votePercent'] = 0;
		} else {
			$option['votePercent'] = (($option['voteCount'] / $totalVotes) * 100);
		}

	}

	$tpl->assign('hasDeletePriv', Session::hasPriv('SURVEY_DELETE_OPTION'));
	$tpl->assign('survey', $survey);
	$tpl->assign('listOptions', $options);
	$tpl->display('viewSurvey.tpl');

	if (Session::isLoggedIn()) {	
		$tpl->assignForm($f);
		$tpl->display('form.tpl');
	} else {
		$tpl->assign('title', 'Only logged in users can vote!');
		$tpl->assign('message', 'To voew on this survey, you need to <a href = "login.php">login</a>!');
		$tpl->display('notification.tpl');
	}
}

require_once 'includes/widgets/footer.php';

?>
