<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
	<title>{$siteTitle}</title>

	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name = "description" content = "{$siteDescription}" />


	<link rel = "shortcut icon" href = "resources/images/westlanFavicon.png" type = "image/png" />
	<link rel = "stylesheet" href = "{$theme}/stylesheets/main.css" type = "text/css" />
	<link rel = "stylesheet" href = "resources/javascript/jquery-ui.css" type = "text/css" />

	<script type = "text/javascript" src = "resources/javascript/jquery.js"></script>
	<script type = "text/javascript" src = "resources/javascript/jquery-ui.js"></script>
	<script type = "text/javascript" src = "resources/javascript/jquery.dataTables.min.js"></script>
	<script type = "text/javascript" src = "resources/javascript/jquery-ui-timepicker-addon.js"></script>
	<script type = "text/javascript" src = "resources/javascript/common.js"></script>
	<script type = "text/javascript" src = "{$theme}/javascript/promo.js"></script>
</head>

<body class = "{if $isMobileBrowser}mobile{else}notmobile{/if}" style = "background-image: url('{$promo}'); ">

<div id = "header">
	<a href = "index.php"><img id = "headerLogo" src = "{$theme}/images/logo.png" alt = "Logo" title = "logo" style = "border:0" /></a>

	<ul class = "navigation">
		<li><a href = "home.php">Home
			{if $newsFeatureEnabled}
			<span class = "arrow">&#9660;</span></a>
			<ul>
				<li><a href = "news.php">News</a></li>
			</ul>
			{else}</a>{/if}
		</li>
		{if $galleryFeatureEnabled}
		<li><a href = "listGalleries.php">Photos</a></li>
		{/if}
		<li><a href = "listEvents.php">Events <span class = "arrow">&#9660;</span></a>
			<ul>
				<li><a href = "listEvents.php">All events</a></li>
				<li><a href = "wpage.php?title=FAQ">FAQ</a></li>
				<li><a href = "wpage.php?title=Sponsors">Sponsors</a></li>
			</ul>
		</li>

		<li>
			{if $IS_LOGGED_IN}
			<a href = "account.php" {if not empty($userHidden)}title = "(sudo'd from: {$userHidden}"{/if}>
				{$username}<span class = "arrow">&#9660;</span>
			</a>
			{else}
			<a href = "account.php">My Account <span class = "arrow">&#9660;</span></a>
			{/if}
			<ul>
				{if $IS_LOGGED_IN}
					<li><a href = "account.php">Control Panel</a></li>
					<li><a href = "profile.php">Profile</a></li>
					<li><a href = "basket.php">Basket</a></li>
					<li><a href = "logout.php">Logout</a></li>
				{else}
					<li><a href = "login.php">Login</a></li>
					<li><a href = "register.php">Register</a></li>
				{/if}
			</ul>
		</li>	

		{if $additionalLinks->hasLinks()}
			{foreach from = $additionalLinks item = link}
				<li><a href = "{$link.url}">{$link.title}</a></li>
			{/foreach}
		{/if}
	</ul>

	{if isset($notification)}
		<div class = "notification {if $notification.karma > 0}good{elseif $notification.karma < 0}bad{/if}" onclick = "$(this).hide()">
		NOTIFICATION {$notification.message}
		</div>
	{/if}
</div>

{if not empty($globalAnnouncement)}
<div class = "box bad" style = "background-color: white;">{$globalAnnouncement}</div>
{/if}
