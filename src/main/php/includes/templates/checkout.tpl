<table>
	<thead>
		<tr>
			<th>Service name</th>
			<th>Cost</th>
			<th>Description</th>
			<th>Select</th>
		</tr>
	</thead>

	<tbody>
		{if not empty($paypalEmail)}
			{include file = "checkout.paypal.tpl"}
		{/if}

		<tr>
			<td>Cash</td>
			<td>{$cost|doubleToGbp}</td>
			<td>If you know any of the Staff, you can pay cash for your ticket when you see them.</td>
			<td><a href = "?action=cash">Cash</a></td>
		</tr>

		<tr>
			<td>Bank transfer</td>
			<td>{$cost|doubleToGbp}</td>
			<td>Payment takes 3-5 working days for banks other than HSBC.</td>
			<td><a href = "?action=bacs">BACS</a></td>
		</tr>

	</tbody>
</table>
