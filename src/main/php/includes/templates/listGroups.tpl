<div class = "box">
	<h2><a href = "account.php">Account</a> &raquo; Groups</h2>

	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Title</th>
				<th>Members</th>
			</tr>
		</thead>

		<tbody>
		{foreach from = "$listGroups" item = "itemGroup"}
			<tr>
				<td>{$itemGroup.id}</td>
				<td><a href = "group.php?action=view&amp;id={$itemGroup.id}" style = "{$itemGroup.css}">{$itemGroup.title}</a></td>
				<td>{$itemGroup.membershipCount}</td>
			</tr>
		{/foreach}
		</tbody>
	</table>
</div>
