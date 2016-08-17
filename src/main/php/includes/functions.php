<?php

use \libAllure\DatabaseFactory;
use \libAllure\Session;
use \libAllure\Inflector;

require_once 'includes/classes/plugins/Mumble.php';
require_once 'includes/classes/Galleries.php';

function atLan() {
	return stripos($_SERVER['REMOTE_ADDR'], getSiteSetting('lanIp')) !== FALSE;
}

function getAcheivements() {
	$sql = 'SELECT a.id, u.username, a.title, a.description FROM acheivments_earnt e LEFT JOIN achievements a ON e.acheiv = a.id LEFT JOIN users u ON e.user = u.id WHERE u.id = :userId ';
	$stmt = DatabaseFactory::getInstance()->prepare($sql);
	$stmt->bindValue(':userId', Session::getUser()->getId());
	$stmt->execute();

	$acheivs = $stmt->fetchAll();

	applyAcheivIcons($acheivs);

	return $acheivs;
}

function applyAcheivIcons(&$acheivs) {
	for ($i = 0; $i < sizeof($acheivs); $i++) {
		$acheivs[$i]['icon'] = 'resources/images/acheivs/' . $acheivs[$i] . '.png';

		if (!file_exists($acheivs[$i]['icon'])) {
			$acheivs[$i]['icon'] = 'resources/images/westlanFavicon.png';
		}
	}
}

function applyAchievements() {
	$stmt = DatabaseFactory::getInstance()->prepare("SELECT a.* FROM achievements a");
	$stmt->execute();

	$signups = getSingleUserSignupsWithStatuses(array('ATTENDED', 'SIGNEDUP', 'PAID', 'CANCELLED', 'STAFF'));
	$signupStatistics = getSignupStatistics($signups);

	foreach ($stmt->fetchAll() as $acheiv) {
		if ($signupStatistics['attended'] < $acheiv['eventsAttended']) {
			continue;
		}

		if ($signupStatistics['cancels'] < $acheiv['eventsCancelled']) {
			continue;
		}

		giveAcheiv(Session::getUser()->getId(), $acheiv['id']);
	}

}

function giveAcheiv($userId, $acheiv) {
	$sql = 'INSERT IGNORE INTO acheivments_earnt (user, acheiv) VALUES (:user, :acheiv) ';
	$stmt = DatabaseFactory::getInstance()->prepare($sql);

	$stmt->bindValue(':user', $userId);
	$stmt->bindValue(':acheiv', $acheiv);
	$stmt->execute();
}

function getPaypalCommission($cost) {
	$comp = getSiteSetting('paypalCommission') . ';';
	$retComp = 0;

	foreach (explode(';', $comp) as $part) {
		if (empty($part)) {
			continue;
		}

		if (strpos($part, '%') !== FALSE) {
			$retComp += (($cost + $retComp) / 100) * floatval($part);
		} else {
			$part = floatval($part);
			$retComp += $part;
		}
	}

	return number_format($retComp, 2);
}

function assignKeys(array $arr, $key) {
	$cpy = array();

	foreach ($arr as $value) {
		$cpy[$value[$key]] = $value;
	}

	return $cpy;
}

function renderForm($formName, $redirect, $showSidebar = true) {
	$_REQUEST['form'] = $formName;
	$_REQUEST['redirect'] = $redirect;

	$showSidebar = false;

	require_once 'form.php';
}

// http://www.dannyherran.com/2011/02/detect-mobile-browseruser-agent-with-php-ipad-iphone-blackberry-and-others/
function isMobileBrowser() {
	if (!isset ($_SERVER['HTTP_USER_AGENT'])) {
		return false;
	}
    if (isset($_REQUEST['forceMobile'])) { return true; }
    $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';

    $mobile_browser = '0';

    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);

    if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', $agent))
        $mobile_browser++;

    if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))
        $mobile_browser++;

    if(isset($_SERVER['HTTP_X_WAP_PROFILE']))
        $mobile_browser++;

    if(isset($_SERVER['HTTP_PROFILE']))
        $mobile_browser++;

    $mobile_ua = substr($agent,0,4);
    $mobile_agents = array(
                        'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
                        'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
                        'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
                        'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
                        'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
                        'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
                        'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
                        'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
                        'wapr','webc','winw','xda','xda-'
                        );

    if(in_array($mobile_ua, $mobile_agents))
        $mobile_browser++;

    if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)
        $mobile_browser++;

    // Pre-final check to reset everything if the user is on Windows
    if(strpos($agent, 'windows') !== false)
        $mobile_browser=0;

    // But WP7 is also Windows, with a slightly different characteristic
    if(strpos($agent, 'windows phone') !== false)
        $mobile_browser++;

    if($mobile_browser>0)
        return true;
    else
        return false;
}

function sendEmail($to, $subject, $content) {
	if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
		throw new Exception('Cannot send email, invalid email address.');
	}

	if (empty($subject)) {
		throw new Exception('Cannot send email, empty subject.');
	}

	if (empty($content)) {
		throw new Exception('Cannot send email, empty content.');
	}



	$content .= "\n" . '- ' . getSiteSetting('emailFrom');

	mail($to, $subject, $content, 'From: ' . getSiteSetting('mailerAddress') . "\n");
}

function getUserSignups($id = null) {
	global $db;

	if (empty($id)) {
		$id = Session::getUser()->getId();
	}

	$sql = 'SELECT s.id, s.status, s.ticketCost AS actualTicketPrice, e.name AS eventName, e.id AS eventId, e.date, e.priceOnDoor, s.comments FROM signups s, events e WHERE s.event = e.id AND s.user = :userId ORDER BY date DESC';
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':userId', $id);
	$stmt->execute();

	$listSignups = $stmt->fetchAll();

	foreach ($listSignups as $k => $itemSignup) {
		$listSignups[$k]['date'] = formatDtString($listSignups[$k]['date']);
	}

	return $listSignups;
}

function setSiteSetting($key, $value) {
	global $db;

	$sql = 'INSERT INTO settings (`key`, value) VALUES (:key1, :value1) ON DUPLICATE KEY UPDATE value = :value2';
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':key1', $key);
	$stmt->bindValue(':value1', $value);
	$stmt->bindValue(':value2', $value);
	$stmt->execute();
}

$settings = array();

function getSiteSetting($key, $default = '') {
	global $settings;
	global $db;

	try {
	if (empty($settings)) {
		$sql = 'SELECT s.`key`, s.value FROM settings s';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->execute();

		foreach ($stmt->fetchAll() as $row) {
			$settings[$row['key']] = $row['value'];
		}
	}
	} catch (Exception $e) {

	}


	if (!isset($settings[$key])) {
		return $default;
	} else {
		return $settings[$key];
	}
}

function getSignupStatistics($signups) {
	$ret['signups'] = 0;
	$ret['cancels'] = 0;
	$ret['noshows'] = 0;
	$ret['attended'] = 0;
	$ret['paid'] = 0;

	foreach ($signups as $signup) {
		switch ($signup['status']) {
			case 'CANCELLED':
				$ret['cancels']++;
				break;
			case 'NOSHOW':
				$ret['noshows']++;
				break;
			case 'STAFF':
			case 'ATTENDED':
				$ret['attended']++;
				$ret['signups']++;
				$ret['paid']++;
				break;	
			case 'CONFIRMED':
			case 'PAID':
				$ret['paid']++;
				$ret['signups']++;
				break;
			case 'WAITING_LIST':
			case 'WAITINGLIST': // old style
			case 'BACS_WAITING':
			case 'PAYPAL_WAITING':
			case 'CASH_IN_POST':
			case 'CHEQUE_IN_POST':
			case 'SIGNEDUP':
				$ret['signups']++;
				break;
		}
	}

	return $ret;
}

function fromRequestRequireAlphanumeric($name) {
	if (!isset($_REQUEST[$name])) {
		throw new Exception('Required variable not set.');
	}

	return filter_var($_REQUEST[$name], FILTER_SANITIZE_STRING);
}

function tplGetContent($params, $smarty) {
	if (!isset($params['title'])) {
		$smarty->trigger_error('The "title" argument is required to the getContent templat function.');
	} else {
		return getContent($params['title']);
	}
}



function implode2(array $pieces, $start, $stop) {
	$ret = '';

	foreach ($pieces as $thing) {
		$ret .= $start . $thing . $stop;
	}

	return $ret;
}

function dbdate() {
	return date('Y-m-d');
}

function requirePrivOrRedirect($privName) {
	if (!Session::hasPriv($privName)) {
		redirect('index.php', 'You do not have the privileges to do this.');
	}
}

function requireLogin() {
	if (!Session::isLoggedIn()) {
		redirect('login.php', 'You must be <a href = "login.php">logged in</a> to view this page.');
	}
}

/**
 * FIXME Some scientists say, this function is shit. Rewrite me to to be less
 * shit please.
 */
function breadcrumbs() {
	$crumbs = func_get_args();
	$countCrumbs = count($crumbs);
	$crumbs2 = array();

	$texts = array_values($crumbs);
	$links = array_keys($crumbs);

	for ($i = 0; $i < $countCrumbs; $i++) {
		if (is_numeric($links[$i])) {
			$links[$i] = $texts[$i];
		}

		if (strpos($texts[$i], '.php')) {
			$texts[$i] = str_replace('.php', null, $texts[$i]);
			$texts[$i] = Inflector::humanize($texts[$i]);

			if (isset($links[$i + 1])) {
				$crumbs2[$links[$i]] = '<a href = "' . $links[$i] .  '">' . $texts[$i] . '</a>';
			} else {
				$crumbs2[$links[$i]] = $texts[$i];
			}
		} else {
			$crumbs2[$links[$i]] =  $texts[$i];
		}
	}

	return implode(' &raquo; ', $crumbs2);
}

function loginPrompt() {
	$_REQUEST['redirect'] = $_SERVER['PHP_SELF'];
	require_once 'login.php';
}

/*
 * Just used in display functions, obviously not calculations.
 */
function doubleToGbp($value) {
	return money_format(getSiteSetting('moneyFormatString'), $value);
}

function isSignupPossibleFromSignupStatus($signupStatus) {
	if ($signupStatus == 'staff' && Session::hasPriv('STAFF_SIGNUP')) {
		return true;
	} elseif ($signupStatus == 'punters' || $signupStatus == 'waitinglist') {
		return true;
	} else if (!Session::hasPriv('SIGNUPS_MODIFY')) {
		return false;
	} else {
		return false;
	}
}

function signupLinks($eventId, $eventSignupStatus, $signupId, $userSignupStatus = null, $userId = null) {
	if (!Session::isLoggedIn()) {
		return 'You must be <a href = "login.php">logged in</a> to signup.';
	}

	if ($userId == null) {
		$userId = Session::getUser()->getId();
		$userSignupStatus = getSignupStatus($userId, $eventId);
	}


	if ($userId != Session::getUser()->getId() && !Session::hasPriv('EDIT_SIGNUPS')) {
		return;
	}

	$signupLinks = array();
	switch ($userSignupStatus) {
		case '';
			if (!isSignupPossibleFromSignupStatus($eventSignupStatus)) {
				return 'Signups are off!';
			}

			$signupLinks[] = '<a href = "signup.php?event=' . $eventId . '">Signup!</a>';
			break;
		case 'SIGNEDUP':
			if ($userId == Session::getUser()->getId()) {
				$signupLinks[] = '<a href = "basket.php?&amp;user=' . $userId . '&amp;event=' . $eventId . '&amp;action=add">Go to basket</a>';

				if (Session::getUser()->hasPriv('CANCEL_OTHERS_SIGNUP')) {
					$signupLinks[] = '<a href = "signup.php?&amp;user=' . $userId . '&amp;event=' . $eventId . '&amp;status=cancelled">Cancel</a>';
				}
			}

			break;
		case 'PAYPAL_WAITING':
			$signupLinks[] = 'Processing payment';
		case 'CONFIRMED':
		case 'PAID':
			$signupLinks[] = '<a href = "signup.php?&amp;user=' . $userId . '&amp;event=' . $eventId . '&amp;status=cancelled">Cancel</a>';
			$signupLinks[] = '<a href = "seatingplan.php?event=' . $eventId . '">Seating plan</a>';
			break;
		case 'CASH_IN_POST':
		case 'CHEQUE_IN_POST':
		case 'BACS_WAITING':
			$signupLinks[] = 'Processing payment';
			break;

		case 'WAITINGLIST': // old style
		case 'WAITING_LIST':
		case 'PAID_CANTATTEND':
		case 'PAID_NOSHOW';
		case 'CANCELLED':
		case 'STAFF':
		case 'ATTENDED':
		case 'NOSHOW':
			break;
		default:
			throw new Exception('Unhandled singup status while working out signup links: ' . $userSignupStatus);
	}

	if (Session::hasPriv('SIGNUPS_MODIFY') && !empty($signupId)) {
		$signupLinks[] .= ' <a href = "updateSignup.php?id=' . $signupId . '">Update</a>';
	}

	return implode(', ', $signupLinks);
}

function formatDtIso($date) {
	$isoStandard = 'Y-m-d H:i';

	if (is_string($date)) {
		return formatDtString($date, $isoStandard);
	} else {
		return formatDt($date, $isoStandard);
	}
}

function formatDtString($dateAsString, $format = null) {
	return formatDt(date_create($dateAsString), $format);
}

function formatDt(DateTime $date, $format = null) {	
	if (empty($format)) {
		if (Session::isLoggedIn()) {
			$dateFormat = Session::getUser()->getData('dateFormat');
		} else {
			$dateFormat = 'Y-m-d H:i';
		}
	} else {
		$dateFormat = $format;
	}

	if ($dateFormat == "opus") {
		return formatDtOpus($date);
	}
   
  $dateTime = empty($dateTime) ? 'Y-m-d H:i' : $dateFormat;

	return $date->format($dateFormat);
}

/**
 * @deprecated Get everyone at once instead.
 * @return String 'notsignedup', 'signedup','paid','cancelled','attended'
 */
function getSignupStatus($userId, $eventId) {
	global $db;

	$sql = 'SELECT status FROM signups WHERE user = :userId AND event = :eventId LIMIT 1';
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':userId', $userId);
	$stmt->bindvalue(':eventId', $eventId);
	$stmt->execute();

	if ($stmt->numRows() == 0) {
		return '';
	}

	$result = $stmt->fetchRow();
	return strtoupper($result['status']);
}

/*
General functions used around the website.
*/
function box($content, $title = null, $class = 'norm') {
	$title = ($title == null) ? null : '<h2>' . $title . '</h2>';

	echo '<div class = "box ' . $class. '">' . $title . $content . '</div>';
}

function logAndRedirect($url, $message) {
	logActivity($message);

	redirect($url, $message);
}

function logActivity($message, $userId = null, $metadata = array()) {
	global $db;

	if ($userId == null) {
		$userId = Session::getUser()->getId();
	}

	$clientIp = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'UNKNOWN';

	$sql = 'INSERT INTO log (message, user, date, ipaddress, associatedUser, associatedEvent) VALUES (:message, :user, now(), :ipaddress, :associatedUser, :associatedEvent)';
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':message', $message);
	$stmt->bindValue(':user', $userId);
	$stmt->bindValue(':ipaddress', $clientIp);
	$stmt->bindValue(':associatedUser', &$metadata['user']);
	$stmt->bindValue(':associatedEvent', &$metadata['event']);
	$stmt->execute();
}

function wikify($content) {
    $content = htmlify($content, false);
    $content = preg_replace('#\\[\\[([\w ]+);([\w ]+)?\\]\\]#', '<a href = "wpage.php?title=$1">$2</a>', $content);
    $content = preg_replace('#\\[\\[([\w ]+)\\]\\]#', '<a href = "wpage.php?title=$1">$1</a>', $content);

    $matches = array(
	'#\\[url\\=(http://.*?)\\](.*?)\\[/url\\]#',
	'#\\[email:([\w\. ]+);([\\w\\@\\.]+)\\]#',
        '#funcFullArticle\\(([\\w ]+)\\)#',
        '#\\_([\w ]+)\\_#',
        '#\\*([\w \d\p{P}]+)\\*#',
        '#-([\w \\&]+)-#',
        '#^=([\w?\'\"\(\)\\\.,/ \\&]+)=#m',
    	'#\\[img\.(\d+)\\](.+)\\[\\/img\\]#',
    	'#\\[img\\](.+)\\[\\/img\\]#',
        '#\\|-([^\\|]+)#sm',
        '#{.(.+?).}#sm',
        '#^\\!(.+?)\n#m',
        '#\\%(.+?)\n#',
        '#\\[list\\]#',
        '#\\[\\/list\\]#',
        '#\n-([\w \p{P}]+)#',
    	'#\[center\]#',
    	'#\[\/center\]#',
		'#\\[space\\]#',
	'#\[br\]#',
    );

    $replacements = array(
        '<a href = "$1" class = "external" title = "This is an external link.">$2</a>',
        '<a href = "mailto:$2" class = "external" title = "This is an email address.">$1</a>',
        '<span class = "subtle">The following is a snippit, there is a full article available: <a href = "wiki.php?title=$1">$1</a>.</span><br />',
        '<em>$1</em>',
    	'<b>$1</b>',
    	'<del>$1</del>',
    	'<h3>$1</h3>' . "\n",
    	'<img src = "$2" width = "$1" alt = "unknown" />',
    	'<img src = "$1" alt = "unknown" />',
        '<tr>\1</tr>',
        '<table>\1</table>',
        '<td>\1</td>',
        '<th>\1</th>',
        '<ul>',
        '</ul>',
        '<li>\1</li>',
    	'<div class = "centered">',
    	'</div>',
		'&nbsp;',
	'<br />',
    );

    $content = preg_replace($matches, $replacements, $content);

    while (strpos($content, 'imgPromo') !== FALSE) {
	$img = Galleries::getRandomImage();
        $rep = '<a href = "viewGalleryImage.php?filename=' . $img['filename'] . '&amp;galleryId=' . $img['galleryId'] . '"><img src = "' . $img['fullPath'] . '" width = "$1" alt = "unknown"></a>';

        $content = preg_replace('#\[imgPromo\.(\d+)\]#', $rep, $content, 1);
    }

    return $content;
}

function getPlugins() {
	global $db;

	$sql = 'SELECT p.id, p.title FROM plugins p WHERE enabled != 0 ORDER BY p.priority';
	$result = $db->query($sql);

	$plugins = array();

	require_once 'includes/classes/Plugin.php';

	foreach ($result->fetchAll() as $plugin) {
		if (file_exists('includes/classes/plugins/' . $plugin['title'] . '.php')) {
			require_once 'includes/classes/plugins/' . $plugin['title'] . '.php';

			$p = $plugin['title'];
			$p = new $p();

			$plugins[] = $p;
		} else {
			throw new Exception('A plugin exists in the database, but it does not have a corresponding plugin file. Delete the row from the plugins table in the databse or upload the plugin file. The plugin name is: ' . $plugin['title']);
		}
	}

	return $plugins;
}

function getContent($pageTitle) {
	global $db;

	$sql = 'SELECT id, page, content FROM page_content WHERE page = :pageTitle LIMIT 1';
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':pageTitle', $pageTitle);
	$stmt->execute();

	if ($stmt->numRows() == 0) {
		if (Session::hasPriv('CONTENT_EDIT')) {
			return '<span class = "bad">Content block "' . $pageTitle . '" does not exist in the database - <a href = "updateContent.php?title=' . $pageTitle . '">create it</a>?</span>';
		} else {
			return '<span class = "bad">Content block "' . $pageTitle . '" does not exist in the database.</span>';
		}
	}

	$content = $stmt->fetchRow();
	$stuff = $content['content'];
	$stuff = stripslashes($stuff);
	$stuff = wikify($stuff);

	if (Session::hasPriv('CONTENT_EDIT')) {
		$stuff .= ' <a href = "updateContent.php?id=' . $content['id'] . '"><img class = "icon" src = "resources/images/icons/edit.png" alt = "edit" /></a>';
	}

	$stuff = parify($stuff);

	return $stuff;
}

function startBox() {
	ob_start();
}

function infobox($message, $karma = 0) {
	switch ($karma) {
		case 1:
			$karma = 'good';
			break;
		case 0:
			$karma = 'neutral';
			break;
		case -1:
			$karma = 'bad';
			break;
	}

	echo '<div class = "infobox ' . $karma . '">' . $message . '</div>';
}

function stopBox($title = null) {
	$content = ob_get_contents();
	ob_end_clean();

	if ($content == null) {
		$content = '&nbsp;';
	}

	box($content, $title);
}

function flushOutputBuffers($leave = 0) {
	while (ob_get_level()> $leave) {
		ob_end_flush();
	}
}

function htmlify($content, $lineSpacing = 1) {
	$content = htmlentities($content, null, null, false);
	$content = stripslashes($content);

	switch ($lineSpacing) {
	case 0:	break;
	case 1:	$content = nl2p($content); break;
	case 2: $content = nl2br($content); break;
	}

	return $content;
}

function nl2p ($s) {
	return parify($s);
}

function parify($s) {
	$s = trim($s);
	$s = explode("\n", $s);

	$paragraphs = array();

	foreach ($s as $paragraph) {
		$paragraph = trim($paragraph);

		// Dont wrap this as a paragraph if;
		// 1) It is short (under 1 char), or
		// 2) If it does not start with a letter, and
		// 3) If it does not start with a number/digit.
		if (strlen($paragraph) <= 1 || (!ctype_alpha($paragraph[0]) && !ctype_digit($paragraph[1])) ) {
			$paragraphs[] = $paragraph;
		} else {
			$paragraphs[] = '<p>' . $paragraph .  '</p>' . "\n";
		}
	}

	return implode($paragraphs);
}

function redirect($url, $reason, $showRedirectionPage = true, $karma = 0) {
	define('REDIRECT', $url);

	if (!$showRedirectionPage) {
		SessionBasedNotifications::getInstance()->add($reason, $karma);
		define('REDIRECT_TIMEOUT', 0);
	} else {
		define('REDIRECT_TIMEOUT', 3);
	}

	require_once 'includes/widgets/header.minimal.php';

	echo '<br />';
	startBox();

	echo '<p>You are being redirected to <a href = "' . $url . '">here</a>.</p>';
	stopBox('Redirecting: ' . $reason);

	require_once 'includes/widgets/footer.minimal.php';
}

function formatDtOpus($dt) {
//	$dt = strtotime($date);

	return $dt->format('l') . ' the ' . convert_number_to_words($dt->format('j')) . $dt->format('S') . ' of ' . $dt->format('F') . ' ' . convert_number_to_words($dt->format('Y'));
}

function convert_number_to_words($number) {
   
    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );
   
    if (!is_numeric($number)) {
        return false;
    }
   
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }
   
    $string = $fraction = null;
   
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
   
    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }
   
    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }
   
    return $string;
}

function parseSeatingPlanObjects($layout) {
	$listObjects = array();

	$row = array();

	foreach (explode(',', $layout) as $itemObject) {
		if (stripos($itemObject, "\n") !== false) {
			$listObjects[] = $row;
			$row = array();
			$itemObject = trim($itemObject);
		}

		$structObject = array(
			'type' => 'none'
		);

		if ($itemObject == 'nl') {
			$structObject['type'] = 'break';
			$row[] = $structObject;
		} else if (is_numeric($itemObject)) {
			$structObject['type'] = 'seat';
			$structObject['index'] = $itemObject;
			$row['seat' . $itemObject] = $structObject;
		} else if ($itemObject == "?") {
			$structObject['type'] = 'block';
			$row[] = $structObject;
		} else if (strpos($itemObject, '!') === 0) {
			$structObject['type'] = 'label';	
			$structObject['text'] = str_replace('!', null, $itemObject);
			$row[] = $structObject;
		} else {
			$structObject['type'] = 'invisible';
			$row[] = $structObject;
		}
	}

	return $listObjects;
}

function applySeatOwnershipToPlan(array $seatingPlan) {
	$sql = 'SELECT id, seat, user FROM seatingplan_seat_selections WHERE event = :event ';
	$stmt = DatabaseFactory::getInstance()->prepare($sql);
	$stmt->bindValue(':event', 0);
	$stmt->execute();
	
	return $seatingPlan;
}

function connectDatabase() {
	try {
		$db = new \libAllure\Database(CFG_DB_DSN, CFG_DB_USER, CFG_DB_PASS);
		\libAllure\DatabaseFactory::registerInstance($db);
	} catch (Exception $e) {
		throw new Exception('Could not connect to database. Check the username, password, host, port and database name.<br />' . $e->getMessage(), null, $e);
	}

	try {
		$maint = getSiteSetting('maintenanceMode', 'NONE');
	} catch (Exception $e) {
		if ($e->getCode() == '42S02') {
			throw new Exception('Settings table not found. Did you import the table schema?', null, $e);
		} else {
			throw new Exception('Unhandled SQL error while getting settings table: ' . $e->getMessage(), null, $e);
		}
	}

	if ($maint === 'NONE') {
		throw new Exception('Essential setting "maintenanceMode" does not exist in the DB. Did you import the initial data?');
	}

	return $db;
}

function addQuotes(&$i) { 
	$i = '"' . $i . '"';
}

function getSingleUserSignupsWithStatuses($statuses, $user = null) {
	if ($user == null) {
		$user = Session::getUser()->getId();
	}

	array_walk($statuses, array(DatabaseFactory::getInstance(), 'quote'));
	array_walk($statuses, 'addQuotes');
	$statusString = implode(", ", $statuses);

	$sql = 'SELECT s.id, e.id AS eventId, e.name, s.status FROM signups s LEFT JOIN events e ON s.event = e.id WHERE s.user = :user AND s.status IN (' . $statusString . ')';
	$stmt = DatabaseFactory::getInstance()->prepare($sql);
	$stmt->bindValue(':user', $user);
	$stmt->execute();

	return $stmt->fetchAll();
}

function checkNotificationNotGuarenteedSeats(&$notifications) {
	foreach (getSingleUserSignupsWithStatuses(array('SIGNEDUP', 'WAITING_LIST')) as $waitingSignup) {
		$list = str_replace('_LIST', '', $waitingSignup['status']);

		$notifications[] = 'You are on the <strong>' . $list . ' LIST</strong> for <a href = "viewEvent.php?id=' . $waitingSignup['eventId'] . '">' . $waitingSignup['name'] . '</a>. This means you are <strong>not guarenteed a seat</strong> until you are <strong>PAID</strong> or are <strong>CONFIRMED</strong>.';
	}
}

function getSurveyCurrentChoice($surveyId) {
	$currentChoice = null;

	if (Session::isLoggedIn()) {
		$sql = 'SELECT so.value FROM survey_votes sv LEFT JOIN survey_options so ON sv.opt = so.id AND sv.user = :username WHERE so.survey = :survey';
		$stmt = DatabaseFactory::getInstance()->prepare($sql);
		$stmt->bindValue(':username', Session::getUser()->getId());
		$stmt->bindValue(':survey', $surveyId);
		$stmt->execute();

		if ($stmt->numRows() > 0) {
			$vote = $stmt->fetchRow();
			$currentChoice = $vote['value'];
		}
	}

	return $currentChoice;
}

?>
