<div class = "box">
	<h2><a href = "listVenues.php">List of Venues</a> &raquo; Venue: venue</h2>
	<p>ID: {$itemVenue.id}</th>
</div>

<div class = "box">
	<h2>Events at this venue</h2>

		{if $listEvents|@count eq 0}
			<p>There are <strong>0</strong> events at this venue.</p>
		{else}

			<table>
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
					</tr>
				</thead>
				
				<tbody>
				{foreach from = $listEvents item = "itemEvent"}
					<tr>
						<td>{$itemEvent.id}</td>
						<td><a href = "viewEvent.php?id={$itemEvent.id}">{$itemEvent.name}</a></td>
					</tr>
				{/foreach}
				</tbody>
			</table>
{/if}
</div>
