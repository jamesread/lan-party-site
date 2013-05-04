<?php

define('INSTALLATION_IN_PROGRESS', true);

require_once 'includes/widgets/header.minimal.php';

if (file_exists('includes/config.php')) {
	redirect('index.php', 'LPS is already installed.');
}

$installer = new Installer();
$installer->runTests();

$tpl->assign('installationTests', $installer->getTestResults());

$installationForm = new FormInstallationQuestions();

if ($installationForm->validate()) {
	$installationForm->process();

	$tpl->assign('configFile', $installationForm->generateConfigFile());
}

$tpl->assignForm($installationForm);
$tpl->display('installer.tpl');

require_once 'includes/widgets/footer.minimal.php';

?>
