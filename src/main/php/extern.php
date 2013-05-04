<?php

require_once 'includes/common.php';

switch ($_GET['source']) {
case 'news':
	$sql = 'SELECT n.id, n.title, n.content, n.date FROM news n ORDER BY n.date DESC LIMIT 30';
	$result = $db->query($sql);

	$listArticles = array();
	$baseUrl = getSiteSetting('baseUrl');

	foreach ($result->fetchAll() as $article) {
		$listArticles[] = array(
			'title' => $article['title'],
			'description' => $article['content'],
			'link' => $baseUrl . '/news.php',
			'id' => $article['id'],
			'date' => date(DATE_RSS, strtotime($article['date'])),
		);
	}

	$tpl->assign('title', 'RSSFEED');
	$tpl->assign('articles', $result->fetchAll());
	$tpl->assign('baseUrl', getSiteSetting('baseUrl'));
	$tpl->assign('rssUrl', getSiteSetting('baseUrl') . 'extern.php?source=news&amp;format=rss');
	$tpl->assign('lastBuildDate', date(DATE_RSS));

	$tpl->assign('listArticles', $listArticles);


	header('Content-Type: application/rss+xml');
	$tpl->display('rssfeed.tpl');

	break;

default:
	die('Unknown source.');
}

?>
