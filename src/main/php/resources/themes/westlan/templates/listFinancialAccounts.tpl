<div class = "box">
	<h2><a href = "account.php">Account</a> &raquo; List of finance accounts</h2>

	{if count($listAccounts) eq 0}
	<p>There are <strong>0</strong> finance accounts.</p>
	{else}
	<table class = "sortable">
		<thead>
			<tr>
				<th>ID</th>
				<th>Title</th>
				<th>Managed By</th>
				<th>Amount in account</th>
			</tr>
		</thead>
		<tbody>
			{foreach from = $listAccounts item = "account"}
			<tr>
				<td>{$account.id}</td>
				<td><a href = "viewFinanceAccount.php?id={$account.id}">{$account.title}</a></td>
				<td><a href = "profile.php?id={$account.managerId}">{$account.managerUsername}</a></td>
				<td>{$account.amount}</td>
			</tr>
			{/foreach}
		</tbody>
	</table>
	{/if}
</div>
