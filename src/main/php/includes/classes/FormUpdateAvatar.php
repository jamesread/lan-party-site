<?php

use \libAllure\Form;
use \libAllure\ElementFile;
use \libAllure\ElementHidden;
use \libAllure\Session;

class FormUpdateAvatar extends Form {
	public function __construct($user) {
		parent::__construct('avatar');
		Session::requirePriv('CHANGE_AVATAR');

		$this->enctype = 'multipart/form-data';

		$this->addElement(new ElementFile('avatar', 'Avatar', null, 'You may upload a png or jpg, maximum size ' . getSiteSetting('avatarMaxWidth') . ' x ' . getSiteSetting('avatarMaxHeight') . ' pixels. Remember to press F5 to refresh your avatar after you have uploaded it!'));
		$this->getElement('avatar')->destinationDir = 'resources/images/avatars/';
		$this->getElement('avatar')->imageMaxW = getSiteSetting('avatarMaxWidth');
		$this->getElement('avatar')->imageMaxH = getSiteSetting('avatarMaxHeight');

		$this->addElement(new ElementHidden('user', 'User', $user));

		$this->addDefaultButtons();
	}

	public function validateExtended() {
		echo $this->getElement('avatar')->wasAnythingUploaded();
	}

	public function process() {
		$this->getElement('avatar')->destinationFilename = $this->getElementValue('user') . '.png';
		$this->getElement('avatar')->savePng();
	}
}

?>
