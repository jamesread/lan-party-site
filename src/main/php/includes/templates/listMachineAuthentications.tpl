<div class = "box">
	<h2>Machine Authentications</h2>

<table id = "machineAuth">
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
			<td>ID: {$itemAuthentication.eventId} - {$itemAuthentication.eventName}<br /><small>Seat Description: {$itemAuthentication.seat}</td>
			<td><a href = "profile.php?id={$itemAuthentication.userId}">{$itemAuthentication.username}</a></td>
		</tr>
		{/foreach}
	</tbody>
</table>
<script type = "text/javascript">
{literal}
$('#machineAuth').dataTable({
	'aaSorting': [[2, "desc"]],
	'sDom': 'flpitpil',
	'iDisplayLength': 100,
});
{/literal}
</script>
</div>
