<tr>
	<td>Paypal</td>
	<td>{$cost|doubleToGbp} <br /><small><nobr>(+ {$costPaypal|doubleToGbp})</nobr></small></td>
	<td>Allows payment as soon as you set a up an account, which takes about 5 minutes to do. </td>
	<td>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_cart" />
			<input type="hidden" name="upload" value = "1" />
			<input type="hidden" name="business" value = "{$paypalEmail}" />
			<input type="hidden" name="currency_code" value = "{$currency}" />

			<input type="hidden" name="cancel_return" value = "{$baseUrl}/checkout.php?action=paypalFail" />
			<input type="hidden" name="return" value = "{$baseUrl}/checkout.php?action=paypalComplete" />
			<input type="hidden" name="shopping_url" value = "{$baseUrl}/basket.php" />

			<input type="hidden" name="amount_1" value = "{$costPaypal}" />
			<input type="hidden" name="item_name_1" value = "Paypal Commission" />

			{foreach from = $listBasketContents item = "itemProduct" key = "ordinal"}
				{math equation = "$ordinal + 2" assign = "ordinalUsable"}
				<input type="hidden" name="amount_{$ordinalUsable}" value = "{$itemProduct.cost}" />
				<input type="hidden" name="item_name_{$ordinalUsable}" value = "{$itemProduct.title} - {$itemProduct.username}" />
			{/foreach}

			<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_buynow_LG.gif" name="submit" alt="PayPal. The safer, easier way to pay online." style = "background-color: white; min-width: 107px; width: 107px; height: 26px; border: 0;"/>
		</form>
	</td>
</tr>
