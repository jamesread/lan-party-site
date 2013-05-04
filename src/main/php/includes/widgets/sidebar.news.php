<?php

use \libAllure\Session;

if (Session::hasPriv('NEWS_ADD')) {
	startBox();
	echo '<dl><dt class = "create"><a href = "news.php?action=new">Add news</a></dt></dl>';
	stopBox('News admin');
}

box('You can get this news in <a href = "extern.php?source=news&amp;format=rss">RSS <img class = "icon" src = "resources/images/icons/rss.gif" alt = "RSS" title = "RSS" /></a> too.');

?>
