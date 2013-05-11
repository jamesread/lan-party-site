<div id = "torso">

<?php

ob_start();

$filename = 'sidebar.' . basename($_SERVER['PHP_SELF']);

if (file_exists('includes/widgets/' . $filename)) {
	require_once $filename;
}

foreach (getPlugins() as $plugin) {
	$plugin->renderSidebar();
}

$sidebar = ob_get_contents();

ob_end_clean();

if (strlen($sidebar) > 0) {

?>

<div id = "sidebarStruct">
<div id = "sidebar">

<?php

echo $sidebar;

?>
</div>
</div>
<div id = "contentStruct">
<div id = "content">
<?php } else { ?>
<div id = "contentStruct">
<div id = "content" style = "margin-left: 0;">
<?php
} 
?>
