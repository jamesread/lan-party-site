<div class = "box">
	<h2>Event: {$event.name}</h2>

	<div style = "display: inline-block; width: 45%; vertical-align: top;">
	<p>Tickets cost <strong>{$event.priceInAdvWithCurrency}</strong> in advance, or <strong>{$event.priceOnDoorWithCurrency}</strong> on the door.</p>
	<p>
		Start time: {$event.start|formatDt}<br />
		Finish time: {$event.finish|formatDt}
	</p>
	</div>

	<div style = "display: inline-block; width: 50%; vertical-align: top;">

	{if $event.inPast}
		<p>There were <strong>{$signupStatistics.signups}</strong> signups for this event.</p>

		{if $event.gallery}
			<p><a href = "viewGallery.php?id={$event.gallery}">View the event gallery</a></p>
		{else}
			<p>There is no gallery for this event.</p>
		{/if}
	{else}
		<strong>Signup links:
			{$signupLinks|default:"(none)"}<br />
		</strong>

		<p>There have been <strong>{$signupStatistics.signups}</strong> signups to this event, there are <strong>{$event.totalSeats}</strong> seats available.</p>
	{/if}
	</div>
</div>
