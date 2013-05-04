<div class = "box">
	<h2>Past events</h2>
	{getContent title = 'pastEvents'}

	{foreach name = "eventsPast" from = $events item = "event"}
		{if not $event.published}
			{if $privViewUnpublishedEvents}
			<span class = "unpublished"><a href = "viewEvent.php?id={$event.id}"><nobr>{$event.name}</nobr></a></span>{if not $smarty.foreach.eventsPast.last},{/if}
			{/if}
		{else}
		<a href = "viewEvent.php?id={$event.id}"><nobr>{$event.name}</nobr></a>{if not $smarty.foreach.eventsPast.last},{/if}
		{/if}
	{/foreach}
</div>
