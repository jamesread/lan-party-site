<div class = "box">
	<h2><a href = "listFinanceAccounts.php">Finance Accounts</a> &raquo; Finance Account</h2>
	<p><strong>Title:</strong> {$account.title}</p>
	<p><strong>ID:</strong> {$account.id}</p>
</div>

<div class = "box">
	<h2>Transactions</h2>

	{if $listTransactions|@count eq 0}
	<p>There are no transactions to show, yet.</p>
	{else}
	<table>
		<thead>
			<tr>
				<th>id</th>
				<th>Amount</th>
				<th>Description</th>
				<th>Timestamp</th>
			</tr>
		</thead>

		<tbody>
			{foreach from = $listTransactions item = "itemTransaction"}
			<tr>
				<td>{$itemTransaction.id}</td>
				<td>{$itemTransaction.amount}</td>
				<td>{$itemTransaction.description}</td>
				<td>{$itemTransaction.timestamp}</th>
			</tr>
			{/foreach}
		</tbody>
	</table>
	{/if}
</div>
