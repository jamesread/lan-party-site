<?php

require_once 'includes/common.php';
require_once 'includes/classes/News.php';
require_once 'includes/classes/FormNewsEdit.php';
require_once 'includes/classes/FormNewsCreate.php';

use \libAllure\Sanitizer;
use \libAllure\Session;

if (!getSiteSetting('newsFeature')) {
	redirect('index.php', 'News feature is disabled.');
}

$action = Sanitizer::getInstance()->filterString('action');

switch ($action) {
case 'add';
case 'new';
	if (!Session::hasPriv('NEWS_ADD')) {
		throw new PermissionsException();
	}

	$f = new FormNewsCreate();

	if ($f->validate()) {
		$f->process();

		logAndRedirect('news.php', 'News item added: ' . $f->getElementValue('title'));
	}

	require_once 'includes/widgets/header.php';
	require_once 'includes/widgets/sidebar.php';

	$tpl->displayForm($f);

	break;
case 'edit';
	$id = intval($_REQUEST['id']);

	$f = new FormNewsEdit($id);

	if ($f->validate()) {
		$f->process();

		logAndRedirect('news.php', 'News item updated: ' . $f->getElementValue('id'));
	}

	require_once 'includes/widgets/header.php';
	require_once 'includes/widgets/sidebar.php';

	$tpl->displayForm($f);

	break;
case 'delete';
	if (!Session::hasPriv('NEWS_DELETE')) {
		throw new PermissionException();
	}

	$id = intval($_REQUEST['id']);

	$sql = 'DELETE FROM news WHERE id = :id ';
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':id', $id);
	$stmt->execute();

	logAndRedirect('news.php', 'News deleted: ' . $id);

	break;

default:
	require_once 'includes/widgets/header.php';
	require_once 'includes/widgets/sidebar.php';

	$news = new News();
	$news->setCount(10);

	while ($article = $news->getNext()) {
		startBox();
		echo '<p><span class = "subtle">Posted on ' . formatDt(new DateTime($article['date'])) . ' by <a href = "profile.php?id=' . $article['author'] . '">' . $article['username'] . '</a>.</span></p>';

		echo htmlify($article['content']);

		if (Session::hasPriv('NEWS_DELETE')) {
			echo '<dl class = "subtle">';
 			echo '<dt><a href = "news.php?action=delete&amp;id=' . $article['id'] . '">Delete</a></dt>';
 			echo '<dt><a href = "news.php?action=edit&amp;id=' . $article['id'] . '">Edit</a></dt>';
 			echo '</dl>';
		}

		stopBox(htmlify($article['title'], false));
	}
}


require_once 'includes/widgets/footer.php';

?>
