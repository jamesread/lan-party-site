
<div class = "scroller box">
{include file = "nextEventBanner.tpl"}
</div>

<div class = "scroller back">&nbsp;</div>

<div class = "scroller box base">
	{if $nextEvent eq NULL}
		<h2>Welcome.</h2>

		{getContent title = "noNextEvent"}
	{else}
		<p style = "text-align: center"><strong>{$nextEvent.date} til {$nextEvent.endDate} at {$nextEvent.venue}</strong></p>
		<p style = "text-align: center"><strong>{$signups.signups} people signed up!</strong></p>

		{getContent title = "home"}
	{/if}
</div>


<div class = "scroller box base">
	{if $nextEvent eq NULL}
		<h2>Welcome.</h2>

		{getContent title = "noNextEvent"}
	{else}
		<p style = "text-align: center"><strong>{$nextEvent.date} til {$nextEvent.endDate} at {$nextEvent.venue}</strong></p>
		<p style = "text-align: center"><strong>{$signups.signups} people signed up!</strong></p>

		{getContent title = "home"}
	{/if}
</div>

