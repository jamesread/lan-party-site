<div class = "box">
	{if $nextEvent eq NULL}
		<h2>Welcome.</h2>

		{getContent title = "noNextEvent"}
	{else}
		<h2 style = "text-align: center;">Next event: <a href = "viewEvent.php?id={$nextEvent.id}">{$nextEvent.name}</a></h2>

		<p style = "text-align: center"><strong>{$nextEvent.date} til {$nextEvent.endDate} at {$nextEvent.venue}</strong></p>
		<p style = "text-align: center"><strong>{$signups.signups} people signed up!</strong></p>

		{getContent title = "home"}
	{/if}
</div>
