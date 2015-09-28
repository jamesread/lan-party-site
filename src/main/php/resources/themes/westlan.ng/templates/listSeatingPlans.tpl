<div class = "box">
	<h2>Seating plan </h2>
	<p>This is the admin view of all available seating plans.</p>

	<table>
		<thead>
			<tr>
				<th>id</th>
				<th>Name</th>
				<th>Used by events</th>
			</tr>
		</thead>

		<tbody>
			{foreach from = $listSeatingPlans item = "itemSeatingPlan"}
			<tr>
				<td>{$itemSeatingPlan.id}</td>
				<td><a href = "viewSeatingPlan.php?id={$itemSeatingPlan.id}">{$itemSeatingPlan.name}</a></td>
				<td>{$itemSeatingPlan.usedBy}</td>
			</tr>
			{/foreach}
		</tbody>
	</table>
</div>
