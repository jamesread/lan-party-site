<div class = "box">
	<h2>Schedule</h2>

	{if $schedule|@count eq 0}
		{getContent title = "emptySchedule"}
	{else}
		{getContent title = "schedule"}
	<table>
		<tr>
			<th width = "20%">Start</th>
			<th colspan = "2">Description</th>
			{if $privEditSchedule}
			<th>Actions</th>
			{/if}
		</tr>

		{foreach from = "$schedule" item = "event"}
		<tr>
			<td>{$event.start}</td>
			<td width = "1%">
				{if isset($event.iconUrl)}
				<img src = "{$event.iconUrl}" alt = "schedule icon" />
				{/if}
			</td>
			<td>{$event.message}</td>
			{if $privEditSchedule}
			<td>{$event.actions}</td>
			{/if}
		</tr>
		{/foreach}
	</table>
	{/if}

	{if isset($form)}
		{include file = "form.tpl" excludeBox = "yes"}
	{/if}
</div>
