<div class = "box">
	<h2>Machine Authentications</h2>

<table class = "sortable">
	<thead>
		<th>IP Address</th>
		<th>MAC</th>
		<th>Event<br /><small>Seat</small></th>
		<th>Username</th>
	</thead>

	<tbody>
		{foreach from = $listAuthentications item = itemAuthentication}
		<tr>
			<td>{$itemAuthentication.ip}</td>
			<td>{$itemAuthentication.mac}</td>
			<td>{$itemAuthentication.eventName}<br /><small>{$itemAuthentication.seat}</td>
			<td><a href = "profile.php?id={$itemAuthentication.userId}">{$itemAuthentication.username}</a></td>
		</tr>
		{/foreach}
	</tbody>
</table>
</div>
