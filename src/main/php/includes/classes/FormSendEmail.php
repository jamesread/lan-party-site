<?php

use \libAllure\Form;
use \libAllure\ElementAlphaNumeric;
use \libAllure\ElementHtml;
use \libAllure\ElementHidden;
use \libAllure\User;
use \libAllure\ElementTextbox;

class FormSendEmail extends Form {
    private $email = null;

	public function __construct($email) {
		parent::__construct('sendEmail', 'Send Email to user');

		$this->email = $email;

		$this->addElement(new ElementHtml('email', null, 'Send to: ' . $this->email));
		$this->addElement(new ElementAlphaNumeric('subject', 'Subject', getSiteSetting('defaultEmailSubject')));
		$this->addElement(new ElementTextbox('content', 'Content', 'Your message here', 'Footers will automatically be applied.'));
		$this->addButtons(Form::BTN_SUBMIT);
	}

	public function process() {
		sendEmail($this->email, $this->getElementValue('subject'), $this->getElementValue('content'));
	}
}

?>
