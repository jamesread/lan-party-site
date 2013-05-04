<?php

require_once 'testsSetup.php';
require_once 'includes/classes/Basket.php';

class BasketTest extends PHPUnit_Framework_Testcase {
	public function testAdd() {
		$event = array(
			'id' => 1,
		);

		Basket::addEvent($event);
	}
}

?>
