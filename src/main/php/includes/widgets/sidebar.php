<div id = "torso">

<div id = "sidebarStruct">
<div id = "sidebar">
<?php

$filename = 'sidebar.' . basename($_SERVER['PHP_SELF']);

if (file_exists('includes/widgets/' . $filename)) {
	require_once $filename;
}

foreach (getPlugins() as $plugin) {
	$plugin->renderSidebar();
}

?>
</div>
</div>

<div id = "contentStruct">
<div id = "content">
