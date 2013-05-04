<div class = "box">
	<h2><a href = "listEvents.php">Events</a> &raquo; Signups stats</h2>

	{if $signupStats|@count eq 0}
		<p>There have never been any signups!</p>
	{else}
		<p>These are the signup statistics.</p>
		<table>
			<thead>
				<tr>
					<th>Event</th>
					<th>Event Start date</th>
					<th>Signup Status</th>
					<th>Count</th>
				</tr>
			</thead>

			<tbody>
				{assign var = "eid" value = ""}
				{foreach from = "$signupStats" item = "signup" key = "k" name = "sus"} 
				<tr>
					<td><a href = "viewEvent.php?id={$signup.event_id}">{$signup.event_name}</a></td>
					<td>{$signup.date}</td>
					<td>{$signup.status}</td>
					<td>{$signup.count}</td>
				</tr>
				{/foreach}
			</tbody>
		</table>
	{/if}
</div>
