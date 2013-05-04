<?php

require_once 'testsSetup.php';

class SignupsTest extends PHPUnit_Framework_TestCase {
	public function testGuestSignupLinks() {
//		$links = signupLinks();

//		$this->assertTrue(is_array($links));
		$this->markTestIncomplete();
	}

	public function testSignupStatisticsStructure() {
		$stats = getSignupStatistics(array());

		$this->assertTrue(is_array($stats));
		$this->assertEquals(sizeof(array_keys($stats)), 5);
		$this->assertTrue(array_key_exists('signups', $stats));
		$this->assertTrue(array_key_exists('cancels', $stats));
		$this->assertTrue(array_key_exists('noshows', $stats));
		$this->assertTrue(array_key_exists('attended', $stats));
		$this->assertTrue(array_key_exists('paid', $stats));
	}
}

?>
