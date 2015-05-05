<div class = "box">
	<h2>Usergroups</h2>

	{if $usergroups|@count eq 0}
		<p>This user does not belong to any groups.</p>	
	{else}
		<table>
			<thead>
				<tr>
					<th>ID</th>
					<th>Title</th>
					<th>Membership Type</th>
					<th>Actions</th>
				</tr>
			</thead>

			<tbody>
				{foreach from = $usergroups item = "group"}
				<tr>
					<td>{$group.id}</td>
					<td><a href = "group.php?action=view&amp;id={$group.id}&amp;" style = "{$group.css}">{$group.title}</a></td>
					<td>{$group.type}</td>
					<td>{$group.actions}</td>
				</tr>
				{/foreach}
			</tbody>
		</table>
	{/if}

	{include file = "form.tpl" excludeBox = "true"}
</div>
