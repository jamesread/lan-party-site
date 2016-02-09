<div class = "box">
	<h2>Privileges</h2>

	<table>
		<thead>
			<tr>
				<th>Privilege</th>
				<th>Source</th>
				<th>Description</th>
			</tr>
		</thead>

		<tbody>
		{foreach from = $listPermissions item = "itemPermission"}
			<tr>
				<td><span class = "good">{$itemPermission.key}</span></td>
				<td>{$itemPermission.source}</td>
				<td>{$itemPermission.description}</td>
			</tr>
		{/foreach}
		</tbody>
	</table>
</div>
