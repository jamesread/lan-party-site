<div class = "box">
	<h2>User: <a href = "profile.php?id={$userId}">{$username}</a> &raquo; Attendance</h2>

	<p>Signed up for <strong>{$stats.signups}</strong> events and attended <strong>{$stats.attended}</strong>.</p>

	{if $attendance|@count ne 0}
		<table>
			<tr>
				<th>Event</th>
				<th>Date</th>
				<th>Ticket price</th>
				{if $privViewSignupComments}
				<th>Comments</th>
				{/if}
				<th>Status</th>
			</tr>

		{foreach from = "$attendance" item = "signup"}
		<tr>
			<td><a href = "viewEvent.php?id={$signup.eventId}"><nobr>{$signup.eventName}</nobr></a></td>
			<td>{$signup.date}</td>
			<td>{$signup.priceOnDoor}</td>
			{if $privViewSignupComments}
			<td>{$signup.comments}</td>
			{/if}
			<td>{$signup.status}</td>
		</tr>
		{/foreach}
		</table>
	{else}
	<p>No signups on record.</p>
	{/if}
</div>
