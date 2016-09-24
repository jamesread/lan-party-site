<?php

require_once 'includes/widgets/header.php';

if (!isset($_REQUEST['txn_id'])) {
	exit; // Probably just a spam bot
}

logActivity('PayPal IPN Notification: ' . print_r($_REQUEST, true));

// ----
// Start PayPal Reference Implementation
// https://developer.paypal.com/docs/classic/ipn/ht_ipn/
// ----

// STEP 1: read POST data
// Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
// Instead, read raw POST data from the input stream.
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
  $keyval = explode ('=', $keyval);
  if (count($keyval) == 2)
    $myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
$req = 'cmd=_notify-validate';
if (function_exists('get_magic_quotes_gpc')) {
  $get_magic_quotes_exists = true;
}
foreach ($myPost as $key => $value) {
  if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
    $value = urlencode(stripslashes($value));
  } else {
    $value = urlencode($value);
  }
  $req .= "&$key=$value";
}

// Step 2: POST IPN data back to PayPal to validate
$ch = curl_init('https://www.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
// In wamp-like environments that do not come bundled with root authority certificates,
// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set
// the directory path of the certificate as shown below:
// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
if ( !($res = curl_exec($ch)) ) {
  logActivity("Got " . curl_error($ch) . " from CURL when processing IPN data");

  curl_close($ch);
  exit;
}
curl_close($ch);

// inspect IPN validation result and act accordingly
if (strcmp ($res, "VERIFIED") == 0) {
  // The IPN is verified, process it
  logActivity('PayPal Transaction ' . $_REQUEST['txn_id'] . '  validated from an IPN. Going to check that the payment status is complete', $_REQUEST['custom']); 

  if ($_REQUEST['payment_status'] == 'Completed') {
    logActivity('PayPal Transaction ' . $_REQUEST['txn_id'] . ' has completed payment. Got ' . $_REQUEST['mc_gross'] . ' from user (id:' . $_REQUEST['custom'] . ')', $_REQUEST['custom']);

	$items = array();
	for ($i = 1; $i <= $_REQUEST['num_cart_items']; $i++) {
	  $items[] = $_REQUEST['item_name' . $i];
	}

	logActivity('PayPal Transaction ' . $_REQUEST['txn_id'] . ' basket contents: ' . print_r($items, true), $_REQUEST['custom']);

	markBasketAsPaidForUser($_REQUEST['custom']);	
  } else {
    logActivity('PayPal Transaction ' . $_REQUEST['txn_id'] . ' is still not completed, it is: ' . $_REQUEST['payment_status'], $_REQUEST['custom']);
  }
} else if (strcmp ($res, "INVALID") == 0) {
  // IPN invalid, log for manual investigation
  logActivity('Got a IPN from PayPal, but when we checked it was not valid. Request: ' . print_r($_REQUEST, true) . ' Result: ' . print_r($res, true));
}

// ----
// End PayPal Reference Implementation
// ----
?>
