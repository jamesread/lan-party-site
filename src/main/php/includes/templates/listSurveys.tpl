<div class = "box">
	<h2>Surveys</h2>

	{if count($listSurveys) eq 0}
		<p>There are <strong>0</strong> surveys for you to view!</p>
	{else}
	<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Title</th>
				<th>Active?</th>
			</tr>
		</thead>

		<tbody>
			{foreach from = "$listSurveys" item = "itemSurvey"}
			<tr>
				<td><a href = "viewSurvey.php?id={$itemSurvey.id}">{$itemSurvey.id}</a></td>
				<td>{$itemSurvey.title}</td>
				<td>{$itemSurvey.active}</td>
			</tr>
			{/foreach}
		</tbody>
	</table>
	{/if}
</div>
