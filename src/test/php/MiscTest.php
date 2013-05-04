<?php

require_once 'testsSetup.php';

class MiscTests extends PHPUnit_Framework_Testcase {
	public function testImplode2() {
		$arr = array(
			'ONE',
			'TWO',
			'THREE',
		);

		$ret = implode2($arr, 'SEP1', 'SEP2');
		
		$this->assertEquals($ret, 'SEP1ONESEP2SEP1TWOSEP2SEP1THREESEP2');
	}

	public function testGbpFormatting() {
		$this->assertEquals(doubletogbp(1), '&pound;1.00');
		$this->assertEquals(doubletogbp(13.37), '&pound;13.37');
		$this->assertEquals(doubletogbp(-1), '&pound;-1.00');
		$this->assertEquals(doubletogbp(192), '&pound;192.00');
		$this->assertEquals(doubletogbp(.5), '&pound;0.50');
		$this->assertEquals(doubletogbp(.02), '&pound;0.02');
	}
}

?>
