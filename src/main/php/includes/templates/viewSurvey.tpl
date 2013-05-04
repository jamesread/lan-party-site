<div class = "box">
	<h2><a href = "listSurveys.php">Surveys</a> &raquo; {$survey.title}</h2>
<table>
	<thead>
		<tr>
			<th>Option</th>
			<th>Votes</th>
			<th>Progress</th>
		</tr>
	</thead>

	<tbody>
		{foreach from = "$listOptions" item = "itemOption"}
			<tr>
				<td>{$itemOption.value}</td>
				<td>{$itemOption.voteCount} vote(s)</td>
				<td>
					<div class = "votebar"><span class = "voteActual" style = "width: {$itemOption.votePercent}%">&nbsp;</span></div>
				
					{if $hasDeletePriv} 
						<a href = "deleteSurveyOption.php?id={$itemOption.id}&amp;surveyId={$survey.id}">Delete option</a>
					{/if}
				</td>
			</tr>
		{/foreach}
	</tbody>

</table>
</div>
