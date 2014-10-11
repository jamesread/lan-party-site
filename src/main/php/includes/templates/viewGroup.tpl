<div class = "box">
	<h2><a href = "account.php">Account</a> &raquo; <a href = "listGroups.php">Groups</a> &raquo; Group: {$group.title}</h2>

	<strong>ID: </strong> {$group.id}<br />
	<strong>Membership count: </strong> {$group.membershipCount}

	<h3>Privileges</h3>

	{if $groupPrivilegesList|@count eq 0}
	<p>There are no permissions assigned to this group.</p>
	{else}
	<table>
		<thead>
			<th>Privilege</th>
			<th>Description</th>
			<th>Actions</th>
		</thead>

		<tbody>
			{foreach from = "$groupPrivilegesList" item = "permission"}
			<tr>
				<td><span class = "good">{$permission.key}</span></td>
				<td>{$permission.description|default:"???"}</td>
				<td><a href = "group.php?action=revoke&amp;group={$group.id}&amp;priv={$permission.id}">Revoke</a></td>
			</tr>
			{/foreach}
		</tbody>
	</table>
	{/if}

	<h3>Members</h3>

	{if $groupMembers|@count eq 0}
		<p>This group does not have any members to show. To add a member, <a href = "users.php">find their profile</a> and use the form there.</p>
	{else}
	<table>
		<thead>
			<th>ID</th>
			<th>Username</th>
			<th>Membership type</th>
			<th>Actions</th>
		</thead>

		<tbody>
			{foreach from = "$groupMembers" item = "member"}
			<tr>
				<td>{$member.id}</td>
				<td><a style = "{$member.groupCss}" href = "profile.php?id={$member.id}">{$member.username}</a></td>
				<td>{$member.type}</td>
				<td><a href = "group.php?action=kick&user={$member.id}&amp;group={$group.id}">Kick</a></td>
			</tr>
			{/foreach}
		</tbody>
	</table>
	{/if}
</div>
