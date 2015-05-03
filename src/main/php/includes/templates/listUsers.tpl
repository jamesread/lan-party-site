<div class = "box">
	<h2><a href = "account.php">Account</a> &raquo; List of Users</h2>

	<p>This is a list of users.</p>

	<table class = "sortable">
		<thead>
			<tr>
				<th>ID</th>
				<th>Username</th>
				<th>Last Login</th>
				<th>Primary group</th>
				<th>Email<br /><small>Real Name</small></th>
			</tr>
		</thead>

		<tbody>
			{foreach from = $listUsers item = "itemUser"}
			<tr>
				<td>{$itemUser.id}</td>
				<td><a href = "profile.php?id={$itemUser.id}" style = "{$itemUser.css}">{$itemUser.username}</a></td>
				<td>{$itemUser.lastLogin}</td>
				<td><a href = "group.php?id={$itemUser.group}&amp;action=view">{$itemUser.groupTitle}</a></td>
				<td>
					{$itemUser.email}<br />
					<small>{$itemUser.real_name}</small>
				</td>
			</tr>
			{/foreach}
		</tbody>
	</table>
</div>
