<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
	<title>{$siteTitle}</title>

	<link rel = "shortcut icon" href = "resources/themes/{$theme}/images/favicon.gif" type = "image/gif" />
	<link rel = "stylesheet" href = "resources/themes/{$theme}/stylesheets/main.css" type = "text/css" />

	{if !empty($redirect)}
	<meta http-equiv = "refresh" content = "{$redirectTimeout};url={$redirect}" />
	{/if}
</head>

<body class = "minimal">
