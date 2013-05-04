<?php

require_once 'includes/common.php';
require_once 'includes/classes/Plugin.php';

use \libAllure\Session;
use \libAllure\Sanitizer;
use \libAllure\ElementHidden;

if (!Session::hasPriv('ADMIN_PLUGINS')) {
	throw new PermissionsException();
}

if (!isset($_REQUEST['action'])) {
	$action = null;
} else {
	$action = Sanitizer::getInstance()->filterString('action');
}

switch ($action) {
case 'settings';
	$id = Sanitizer::getInstance()->filterUint('id');
	$sql = 'SELECT p.id, p.title FROM plugins p WHERE p.id = :id LIMIT 1';
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':id', $id);
	$stmt->execute();

	$plugin = $stmt->fetchRow();

	if (empty($plugin)) {
		throw new Exception('Plugin not found.');
	}

	require_once 'includes/classes/plugins/' . $plugin['title'] . '.php';
	$pluginInstance = new $plugin['title']();

	if (!($pluginInstance instanceof Plugin)) {
		throw new Exception('That is not a plugin.');
	}

	$f = $pluginInstance->getSettingsForm();
	$f->addElementHidden('action', 'settings');
	$f->addElementHidden('id', $plugin['id']);

	if (!($f instanceof \libAllure\Form)) {
		require_once 'includes/widgets/header.php';
		echo 'No settings for that plugin.';
		return;
	}

	if ($f->validate()) {
		$f->process();

		redirect('plugins.php', 'Plugin settings saved.');
	}

	require_once 'includes/widgets/header.php';

	$tpl->assignForm($f);
	$tpl->display('form.tpl');

	require_once 'includes/widgets/footer.php';

	break;
case 'toggle';
	$id = Sanitizer::getInstance()->filterUint('id');
	$sql = 'UPDATE plugins SET enabled = !enabled WHERE id = :id';
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':id', $id);
	$stmt->execute();

	redirect('plugins.php', 'Plugin status toggled. ');

	break;
default:
	require_once 'includes/widgets/header.php';
	require_once 'includes/widgets/sidebar.php';

	$sql = 'SELECT id, title, enabled FROM plugins';
	$result = $db->query($sql);

	$tpl->assign('listPlugins', $result->fetchAll());
	$tpl->display('listPlugins.tpl');
}

require_once 'includes/widgets/footer.php';

?>
