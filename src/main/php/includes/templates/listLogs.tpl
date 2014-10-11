<div class = "box">
	<h2><a href = "account.php">Account</a> &raquo; Logs</a></h2>
	
	<p>By default, the last 50 log messages are shown. All logs are retained in the database.</p>

	<table>
		<thead>
			<thead>
				<tr>
					<th>Date<br><small>ID</small></th>
					<th>User<br /><small>IP</small></th>
					<th>Message</th>
				</tr>
			</thead>
		</thead>

		<tbody>
		{foreach from = "$listLogs" item = "itemLog"}
		<tr>
			<td><nobr>{$itemLog.date}</nobr><br /><small>{$itemLog.id}</small></td>
			<td>
				<a href = "profile.php?id={$itemLog.user_id}" style = "{$itemLog.userGroupCss}">{$itemLog.username}</a><br />
				<small>{$itemLog.ipAddress}</small>
			</td>
			<td>{$itemLog.message}</td>
		</tr>
		{/foreach}
		</tbody>
	</table>
</div>
