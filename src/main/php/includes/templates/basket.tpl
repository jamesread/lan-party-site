<div class = "box">
	<h2>Your basket</h2>

	{if $basketIsEmpty}
		{getContent title = "emptyBasket"}
	{else}
		{getContent title = "viewBasket"}
		<table>
			<thead>
				<tr>
					<th>Event</th>
					<th>Ticket is for username</th>
					<th>Event ticket price</th>
					<th>Actions</th>
				</tr>
			</thead>

			<tbody>
				{foreach from = "$basketItems" item = "product"}
				<tr>
					<td><a href = "viewEvent.php?id={$product.eventId}">{$product.title}</td>
					<td>{$product.username}</td>
					<td>{$product.cost|doubleToGbp}</td>
					<td><a href = "basket.php?user={$product.userId}&amp;event={$product.eventId}&amp;action=delete">Remove ticket</a></td>

				</tr>
				{/foreach}
			</tbody>
		</table>

		<br /><strong>Total cost:</strong> {$basketTotal|doubleToGbp}<br />

		{getContent title = "payForBasket"}
		<form style = "text-align: right;" action = "checkout.php"><input type = "submit" value = "Proceed to checkout" /></form>
	{/if}

</div>

<div class = "box">
	<h2>Buy more stuff</h2>

	{if $signupableEvents|@count eq 0}
		{getContent title = "nothingToSignupTo"}
	{else}
		<div style = "float: left; width: 40%;">
		{if $addToBasketHasEvents}
			{include file = "form.tpl" form = "$addToBasketform" elements = "$addToBasketelements" excludeBox = "yes"}
		{else}
			<p>There are no events to add to your basket.</p>	
		{/if}
		</div>

		<div style = "float: left; width: 40%;">
		{include file = "form.tpl" form = "$payForFriendform" elements = "$payForFriendelements" excludeBox = "yes"}
		</div>

		<div class = "clearer" />
	{/if}
</div>
