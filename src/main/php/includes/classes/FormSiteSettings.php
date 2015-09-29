<?php

use \libAllure\Form;
use \libAllure\ElementCheckbox;
use \libAllure\ElementInput;
use \libAllure\Session;
use \libAllure\ElementSelect;
use \libAllure\ElementNumeric;
use \libAllure\ElementAlphaNumeric;
use \libAllure\ElementHtml;

class FormSiteSettings extends Form {
	public function __construct() {
		$this->settings = $this->getSettings();

		Session::requirePriv('SITE_SETTINGS');

		$this->addSection('General');
		$this->addElement(new ElementCheckbox('maintenanceMode', 'Maintenance Mode', $this->settings['maintenanceMode'], 'Prevesnts user logins, allowing you to quiesse the site.'));
		$this->addElement(new ElementCheckbox('lanMode', 'LAN Mode', $this->settings['lanMode'], 'Makes this copy of the site a simplified version for the intranet at a LAN.'));
		$this->addElement(new ElementCheckbox('masterConnectionAvailable', 'Master connection available', $this->settings['masterConnectionAvailable'], 'When the site is in LAN mode, can it connect to the master site?'));
		$this->addElement(new ElementInput('masterConnectionUrl', 'Master connection URL', $this->settings['masterConnectionUrl'], 'When the site is in LAN mode, where is the master site?'));
		$this->addElement(new ElementInput('baseUrl', 'Base URL', $this->settings['baseUrl']));
		$this->addElement(new ElementInput('siteTitle', 'Site title', $this->settings['siteTitle']));
		$this->addElement(new ElementInput('siteDescription', 'Site description (for META tags)', &$this->settings['siteDescription']));
		$this->addElement(new ElementAlphaNumeric('copyright', 'Copyright', $this->settings['copyright']));
		$this->addElement($this->getElementSiteTheme($this->settings['theme']));
		$this->addElement(new ElementInput('globalAnnouncement', 'Global Announcement', $this->settings['globalAnnouncement'], 'An announcement displayed on every page of the site'))->setMinMaxLengths(0, 256);
		$this->addElement(new ElementInput('cookieDomain', 'Cookie Domain', &$this->settings['cookieDomain']));

		$this->addSection('Enabled site features');
		$this->addElement(new ElementCheckbox('newsFeature', 'News feature', $this->settings['newsFeature']));
		$this->addElement(new ElementCheckbox('galleryFeature', 'Gallery feature', $this->settings['galleryFeature']));

		$this->addSection('Avatars');
		$this->addElement(new ElementNumeric('avatarMaxWidth', 'Max width', $this->settings['avatarMaxWidth'], 'Max width of avatar in pixels.'));
		$this->getElement('avatarMaxWidth')->setBounds(20, 200);
		$this->addElement(new ElementNumeric('avatarMaxHeight', 'Max height', $this->settings['avatarMaxHeight'], 'Max height of avatar in pixels.'));
		$this->getElement('avatarMaxHeight')->setBounds(20, 200);

		$this->addSection('Email');
		$this->addElement(new ElementInput('emailFrom', 'Email from', $this->settings['emailFrom'], 'In the footer of emails, who is the email from?'));
		$this->addElement(new ElementInput('mailerAddress', 'Mailer address ', $this->settings['mailerAddress']));
		$this->addElement(new ElementInput('defaultEmailSubject', 'Default email subject', $this->settings['defaultEmailSubject']));

		$this->addSection('Finance &amp; Currency');
		$this->addElement(new ElementInput('currency', 'Currency', $this->settings['currency']))->setMinMaxLengths(0, 3);
		$this->addElement(new ElementInput('moneyFormatString', 'Money Format String', $this->settings['moneyFormatString']))->setMinMaxLengths(0, 99);
		$this->getElement('moneyFormatString')->addSuggestedValue('£%.2n', 'UK Money format');
		$this->getElement('moneyFormatString')->addSuggestedValue('%i', 'International format');

		$this->addSection('Pay Pal');
		$this->addElement(new ElementInput('paypalEmail', 'Paypal Email', $this->settings['paypalEmail']))->setRequired(false);
		$this->addElement(new ElementInput('paypalCommission', 'Paypal commission', $this->settings['paypalCommission']));
		$this->getElement('paypalCommission')->setRequired(false);
		$this->getElement('paypalCommission')->setMinMaxLengths(0, 64);

		$this->addDefaultButtons();
	}

	private function getElementSiteTheme($settingTheme) {
		$el = new ElementSelect('theme', 'Theme', $settingTheme);

		foreach (scandir('resources/themes/') as $theme) {
			if ($theme[0] == '.') {
				continue;
			}

			$el->addOption($theme);
		}

		return $el;
	}

	private function getSettings() {
		global $db;
		$sql = 'SELECT `key`, value FROM settings';

		$this->settings = array();

		foreach ($db->query($sql)->fetchAll() as $setting) {
			$this->settings[$setting['key']] = $setting['value'];
		}

		return $this->settings;
	}

	private function logSettingChange($settingKey) {
		if ($this->settings[$settingKey] != $this->getElementValue($settingKey)) {
			logActivity('Changed site setting: ' . $settingKey, Session::getUser()->getId());
		}
	}

	public function process() {
		global $db;

		$this->logSettingChange('globalAnnouncement');

		$sql = 'INSERT INTO settings (`key`, value) VALUES (:key, :value) ON DUPLICATE KEY UPDATE value = :value2';
		$stmt = $db->prepare($sql);

		foreach ($this->getElements() as $el) {
			if (is_array($el)) {
				continue;
			}

			if ($el instanceof ElementHtml) {
				continue;
			}

			$stmt->bindValue(':key', $el->getName());
			$stmt->bindValue(':value', $el->getValue());
			$stmt->bindValue(':value2', $el->getValue());
			$stmt->execute();
		}

		redirect('account.php', 'Site settings saved.');
	}
}

?>
