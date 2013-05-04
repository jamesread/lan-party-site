<div class = "box">
	<h2>Controls</h2>
	<h3>Add</h3>
	<ul>
		<li><a href = "#" onclick = "newVerticalTable()">Vertical Table</a></li>
		<li><a href = "#" onclick = "newHorizontalTable()">Horizontal Table</a></li>
		<li><a href = "#" onclick = "newSeat()">Seat</a></li>
	</ul>
	<h3>Other</h3>
	<ul>
		<li><a href = "#" onclick = "save()">Save</a></li>
		<li><a href = "#" onclick = "load()">Load</a></li>
	</ul>
</div>
<?php

class FormPlanObjects extends Form {
	public function __construct() {
		parent::__construct('formPlanObjects');

		$el = new ElementSelect('object', 'Object');
		$el->addOption('chair');
		$el->addOption('desk');
		$this->addElement($el);

		$this->addElement(new ElementInput('bgcolor', 'Background color'));
		$this->addElement(new ElementInput('width', 'Width'));
		$this->addElement(new ElementInput('height', 'Height'));

		$el1 = new ElementButton('add', 'Add');
		$el2 = new ElementButton('edit', 'Edit');

		$this->addElementGroup(array($el1, $el2));
	}
}

class FormPlanOptions extends Form {
	public function __construct() {
		parent::__construct('formPlanOptions');

		$this->addElement(new ElementInput('xgrid', 'xgrid', 20));
		$this->addElement(new ElementInput('ygrid', 'ygrid', 20));

		$this->addButtons(Form::BTN_SUBMIT);
	}
}

startbox();
$f = new FormPlanObjects();
$f->display();
stopbox('Objects');

startbox();
$f = new FormPlanOptions();
$f->display();
stopbox('Plan options');

?>
