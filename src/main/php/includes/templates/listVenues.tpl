<div class = "box">
	<h2>Venues.</h2>

	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Used by # of events</th>
			</tr>
		</thead>

		<tbody>
		{foreach from = "$listVenues" item = "itemVenue"}
			<tr>
				<td><a href = "viewVenue.php?id={$itemVenue.id}">{$itemVenue.id}</a></td>
				<td>{$itemVenue.name}</td>
				<td>{$itemVenue.usageCount}</td>
			</tr>
		{/foreach}
		</tbody>
	</table>
</div>
