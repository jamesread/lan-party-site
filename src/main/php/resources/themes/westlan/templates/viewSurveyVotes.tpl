<div class = "box">
	<h2>Surveys &raquo; <a href = "viewSurvey.php?id={$survey.id}">{$survey.title}</a> &raquo; Details</h2>
<table>
	<thead>
		<tr>
			<th>Voter</th>
			<th>Voted for</th>
		</tr>
	</thead>

	<tbody>
		{foreach from = $listOptions item = "itemOption"}
		<tr>
			<td>{$itemOption.username}</td>
			<td>{$itemOption.value}</td>
		</tr>
		{/foreach}
	</body>
</table>

</div>
