<div class = "box">
	<h2><a href = "account.php">Account</a> &raquo; System Status</h2>
	<table>
		<thead>
			<tr>
				<th>Stat</th>
				<th>Value</th>
			</tr>
		</thead>

		<tbody>
			{foreach from = "$listStatistics" item = "itemStat"}
				<tr>
					<td>{$itemStat.title}</td>
					<td><strong>{$itemStat.value}</strong></td>
				</tr>
			{/foreach}
		</tbody>
	</table>
</div>
