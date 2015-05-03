<div class = "box">
<h2><a href = "account.php">Account</a> &raquo; Plugins</h2>

{if count($listPlugins) eq 0}
	<p>No plugins are currently installed.</p>
{else}
	<p>This is a list of plugins.</p>

	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Title</th>
				<th>Enabled</th>
				<th>Actions</th>
			</tr>
		</thead>

		<tbody>
		{foreach from = $listPlugins item = "itemPlugin"}
			</tr>
				<td>{$itemPlugin.id}</td>
				<td>{$itemPlugin.title}</td>
				<td>{if $itemPlugin.enabled}Yes{else}No{/if}</td>
				<td>
					<a href = "plugins.php?action=toggle&amp;id={$itemPlugin.id}">Toggle</a>
					<a href = "plugins.php?action=settings&amp;id={$itemPlugin.id}">Settings</a>
				</td>
			<tr>
		{/foreach}
		</tbody>
	</table>
{/if}
</div>
