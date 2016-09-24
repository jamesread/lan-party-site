<?php

use \libAllure\Form;
use \libAllure\Session;
use \libAllure\ElementHidden;
use \libAllure\ElementEmail;
use \libAllure\Password;
use \libAllure\ElementCheckBox;
use \libAllure\ElementAlphaNumeric;
use \libAllure\ElementInputRegex;
use \libAllure\ElementPassword;
use \libAllure\ElementInput;
use \libAllure\ElementSelect;
use \libAllure\User;
use \libAllure\AuthBackend;

class FormUpdateProfile extends Form {
	public function __construct($userId = null) {	
		parent::__construct('formUpdateProfile', 'Update profile');	

		if ($userId == null) {
			$user = Session::getUser();
		} else if (($userId != Session::getUser()->getId())) {
			requirePrivOrRedirect('EDIT_USERS', 'index.php');

			$user = User::getUserById($userId);
		} else {
			$user = Session::getUser();
		}

		$this->user = $user;

		$this->addSection('Bio');
		$this->addElement(new ElementHidden('action', null, 'edit'));
		$this->addElement(new ElementHidden('user', null, $user->getId()));
		$this->addElement(new ElementEmail('email', 'E-Mail Address', $user->getData('email')));
		$elementRealName = $this->addElement(new ElementAlphaNumeric('realName', 'Real Name', $user->getData('real_name')));
		$elementRealName->setMinMaxLengths(0, 32);
	
		$elementLocation = $this->addElement(new ElementAlphaNumeric('location', 'Location', $user->getData('location')));
		$elementLocation->setMinMaxLengths(0, 64);

		$this->addElement(new ElementInputRegex('mobileNo', 'Mobile No.', $user->getData('mobileNo')))->setMinMaxLengths(0, 16);
		$this->getElement('mobileNo')->setPattern('#^[\d ]+$#', 'numbers and spaces');
		$this->getElement('mobileNo')->setMinMaxLengths(11, 15);

		$this->addSection('Preferences');
		$this->addElement(new ElementCheckbox('mailingList', 'Mailing list', $user->getData('mailingList')));
		$this->addElement($this->getElementSiteTheme($user->getData('theme')));

		$now = date_create();
		$elementDateFormat = $this->addElement(new ElementSelect('dateFormat', 'Date format', $user->getData('dateFormat')));
		$elementDateFormat->addOption('ISO date format (recommended): ' . formatDt($now, 'Y-m-d'), 'Y-m-d H:i');
		$elementDateFormat->addOption('UK, numeric date format: ' . formatDt($now, 'd-m-Y'), 'd-m-Y');
		$elementDateFormat->addOption('UK, long date format: ' . formatDt($now, 'jS M Y'), 'jS M Y');
		$elementDateFormat->addOption('USA, numeric date format: ' . formatDt($now, 'm-d-Y'), 'm-d-Y');
		$elementDateFormat->addOption('Opus date format: ' . formatDtOpus($now), 'opus');

		$this->addSection('Change password');

		if (Session::getUser()->getUsername() == $user->getUsername()) {
			$this->addElement(new ElementPassword('passwordCurrent', 'Current password', null, 'Fill this field out if you would like to change your password.'));
			$this->getElement('passwordCurrent')->setOptional(true);
		}

		$this->addElement(new ElementPassword('password1', 'New Password', null))->setOptional(true);
		$this->addElement(new ElementPassword('password2', 'New Password (confirm)', null))->setOptional(true);

		if (Session::getUser()->hasPriv('EDIT_BANS')) {
			$this->addSection('Banning and admin stuff');
			$this->addElement(new ElementInput('bannedReason', 'Banned reason', $user->getData('bannedReason'), 'Enter a reason to ban this user. Leave it blank to keep the user active.'));
			$this->getElement('bannedReason')->addSuggestedValue('', 'Clear ban');
			$this->getElement('bannedReason')->setMinMaxLengths(0, 256);

			$this->addElement(new ElementCheckbox('emailFlagged', 'Email flagged?', $user->getData('emailFlagged')));
		}

		$this->addButtons(Form::BTN_SUBMIT);
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

	public function validateExtended() {
		$this->validateEmail();

		if ($this->getElementValue('password1') != '') {
			if (strlen($this->getElementValue('password1')) < 6) {
				$this->getElement('password1')->setValidationError('User a longer password, at least 6 chars.');
			}

			if ($this->getElementValue('password1') != $this->getElementValue('password2')) {
				$this->getElement('password2')->setValidationError('Those passwords do not match.');
			}

			if ($this->user->getUsername() == Session::getUser()->getUsername()) {
				$passwordCurrent = $this->getElementValue('passwordCurrent');

				if (empty($passwordCurrent)) {
					$this->getElement('passwordCurrent')->setValidationError('If changing your password, you must enter your current password first.');
				}

				try { 
					AuthBackend::getBackend()->checkCredentials($this->user->getUsername(), $passwordCurrent);
				} catch (Exception $e) {
					$this->getElement('passwordCurrent')->setValidationError('This does not appear to be your current password.');
				}
			}

		}
	}

	public function validateEmail() {
		global $db;

		$sql = 'SELECT email FROM users WHERE email = :email AND id != :userId LIMIT 1';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':email', $this->getElementValue('email'));
		$stmt->bindValue(':userId', $this->getElementValue('user'));
		$stmt->execute();

		if ($stmt->numRows() != 0) {
			$this->setElementError('email', 'That address is already in use by another member.');
		}
	}

	private function bindUser(&$stmt) {
		if (Session::hasPriv('EDIT_USERS')) {
			$stmt->bindValue(':id', $this->getElementValue('user'));
		} else {
			$stmt->bindValue(':id', Session::getUser()->getId());
		}
	}

	public function process() {
		global $db;


		$sql = 'UPDATE users SET dateFormat = :dateFormat, email = :email, real_name = :realName, location = :location, mobileNo = :mobileNo, emailFlagged = 0, mailingList = :mailingList, theme = :theme WHERE id = :id LIMIT 1';
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':dateFormat', $this->getElementValue('dateFormat'));
		$stmt->bindValue(':email', $this->getElementValue('email'));
		$stmt->bindValue(':realName', $this->getElementValue('realName'));
		$stmt->bindValue(':location', $this->getElementValue('location'));
		$stmt->bindValue(':mobileNo', $this->getElementValue('mobileNo'));
		$stmt->bindValue(':mailingList', $this->getElementValue('mailingList'));
		$stmt->bindValue(':theme', $this->getElementValue('theme'));
		$this->bindUser($stmt);

		$stmt->execute();

		if ($this->getElementValue('password1') != '') {
			$this->processPassword();
		}

		$this->processAdminFields();

		// Re-cache the user data.
		Session::getUser()->getData('username', false); 
	}

	private function processAdminFields() {
		global $db;

		if (Session::getUser()->hasPriv('EDIT_BANS')) {
			$sql = 'UPDATE users SET bannedReason = :bannedReason, emailFlagged = :emailFlagged WHERE id = :id LIMIT 1';
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':bannedReason', $this->getElementValue('bannedReason'));
			$stmt->bindValue(':emailFlagged', $this->getElementValue('emailFlagged'));
			$this->bindUser($stmt);
			$stmt->execute();
		}
	}

	private function processPassword() {
		global $db;

		$sql = 'UPDATE users SET password = :password1 WHERE id = :id LIMIT 1';
		$stmt = $db->prepare($sql);
		$password = sha1($this->getElementValue('password1') . CFG_PASSWORD_SALT);
		$stmt->bindValue(':password1', $password);
		$this->bindUser($stmt);
		$stmt->execute();
	}
}

?>
