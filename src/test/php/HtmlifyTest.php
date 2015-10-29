<?php

require_once 'testsSetup.php';

class HtmlifyTest extends PHPUnit_Framework_Testcase {
	public function testTagsMode2() {
		$original = 'hi <br /> bye';
		$expected = 'hi &lt;br /&gt; bye';

		$this->assertEquals($expected, htmlify($original, 2));
	}

	public function testEntitesMode2() {
		$original = 'foo £1337 bar';
		$expected = 'foo &pound;1337 bar';

		$this->assertEquals($expected, htmlify($original, 2));
	}

	public function testExampleSignupMode2() {
		$original = <<<EOF
2015-07-05 14:31:51 Signup created. 
 2015-07-05 14:31:51 ( system ) - User self signup. User requirement: testing <3
2015-07-15 19:18:37 (wishy) - Paypal - &pound;22 - Bob Status changed from PAYPAL_WAITING to PAID.  
 2015-10-16 21:38:35 ( system ) - Authenticated machine: 00:11:22:33:44:55:66
EOF;

		$expected = <<<EOF
2015-07-05 14:31:51 Signup created. <br />
 2015-07-05 14:31:51 ( system ) - User self signup. User requirement: testing &lt;3<br />
2015-07-15 19:18:37 (wishy) - Paypal - &pound;22 - Bob Status changed from PAYPAL_WAITING to PAID.  <br />
 2015-10-16 21:38:35 ( system ) - Authenticated machine: 00:11:22:33:44:55:66
EOF;

		$this->assertEquals($expected, htmlify($original, 2));

	}
}

?>
