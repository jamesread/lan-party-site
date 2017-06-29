<div class = "box highlight">
{include file = "nextEventBanner.tpl"}
</div>

<div class = "box base">
	{if $nextEvent eq NULL}
		<h2>Welcome.</h2>

		{getContent title = "noNextEvent"}
	{else}
		{getContent title = "home"}
	{/if}
</div>
