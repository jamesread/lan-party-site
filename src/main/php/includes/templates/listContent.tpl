<div class = "box">
	<h2><a href = "account.php">Account</a> &raquo; Content</h2>

	<p>Here is a list of content.</p>
	
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Page</th>
				<th>Updated</th>
				<th>Updated By</th>
				<th>Actions</th>
			</tr>
		</thead>

		<tbody>
		{foreach from = "$listContent" item = "itemContent"}
			<tr>
				<td>{$itemContent.id}</td>
				<td><a href = "updateContent.php?id={$itemContent.id}">{$itemContent.page}</a></td>
				<td>{$itemContent.updated}</td>
				<td><a href = "profile.php?id={$itemContent.user}" style = "{$itemContent.userGroupCss}">{$itemContent.username}</a></td>
				<td><a href = "wpage.php?title={$itemContent.page}">View</a></td>
			</tr>
		{/foreach}
		</tbody>
	</table>
</div>
