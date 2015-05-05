<div class = "box">
	<h2>Finances</h2>
	<p>These are your finance accounts.</p>

	{foreach from = $listAccounts item = "account"}
		<table>
			<tbody>
				<tr>
					<td>{$account.title}</td>
				</tr>
			</tbody>
		</table>
	{/foreach}
</div>
