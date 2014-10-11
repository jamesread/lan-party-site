<div class = "box">
	<h2>Upcoming events</h2>

	{if $events|@count eq 0} 
		{getContent title = 'noNextEvent'}
	{else}
	<table>
		<tr>
			<th>Name</th>
			<th>Duration</th>
			<th>Venue</th>
		</tr>

		{foreach from = "$events" item = "event"}
            {if not $event.published}
                {if $privViewUnpublishedEvents}
		<tr>
			<td><a class = "unpublished" href = "viewEvent.php?id={$event.id}">{$event.name}</a></td>
			<td>{$event.date} - {$event.finish}</td>
			<td>{$event.venue}</td>
		</tr>
                {/if}
            {else}
		<tr>
			<td><a href = "viewEvent.php?id={$event.id}">{$event.name}</a></td>
			<td>{$event.date} - {$event.finish}</td>
			<td>{$event.venue}</td>
		</tr>
            {/if}
		{/foreach}
	</table>

	{getContent title = 'upcomingEventsBottom'}

	{/if}

</div>
