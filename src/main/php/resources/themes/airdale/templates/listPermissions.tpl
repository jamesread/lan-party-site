<div class = "box">
	<h2><a href = "account.php">Account</a> &raquo; Permissions</h2>

	<p>The system has lots of permissions. Permissions can be assigned to groups or users. When assigned, it is called a group privilege or a user privilege. </p>

	{if $permissionsList|@count eq 0}
	<p>There are no permissions defined!</p>
	{else}
	<table>
		<thead>
			<th>Privilege</th>
			<th>Description</th>
		</thead>

		<tbody>
			{foreach from = $permissionsList item = "permission"}
			<tr>
				<td><a href = "updatePermission.php?id={$permission.key}">{$permission.priv}</a></td>
				<td>{$permission.description|default:"???"}</td>
			</tr>
			{/foreach}
		</tbody>
	</table>
	{/if}

</div>
