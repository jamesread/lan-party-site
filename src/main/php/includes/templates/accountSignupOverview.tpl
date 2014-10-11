<div class = "box">
	<h2>Events</h2>

	<p>You have signed up for <strong>{$userSignupStatistics.signups}</strong> events and attended <strong>{$userSignupStatistics.attended}</strong>. {if $privViewAttendance}For more detail, checkout your <a href = "viewAttendance.php">attendence record</a>.{/if}</p>

	{if $userSignupStatistics.signups gt 0}
	<table>
		<thead>
			<tr>
				<th>Event</th>
				<th>Date</th>
				<th>Status</th>
			</tr>
		</thead>

		<tbody>
		{foreach name = "eventSignups" from = "$userEventSignups" item = "signup"}
			<tr>
				<td><a href = "viewEvent.php?id={$signup.eventId}">{$signup.eventName}</a></td>
				<td>{$signup.date}</td>
				<td>{$signup.status}</td>
			</tr>
		{/foreach}
		</tbody>
	</table>
	{/if}
</div>
