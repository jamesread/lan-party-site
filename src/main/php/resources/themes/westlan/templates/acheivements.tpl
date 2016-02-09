<div class = "box">
	<h2><a href = "listAchievements.php">Acheivements</a></h2>

	{if empty($acheivements)}
	<p>You have not earned any acheivements yet!</p>
	{else}
	<table>
	{foreach from = "$acheivements" item = "acheiv"}
		<tr>
			<td><img width = "50" src = "{$acheiv.icon}" alt = "acheive icon" /></td>
			<td><strong>{$acheiv.title}</strong></td>
			<td>{$acheiv.description}</td>
		</tr>
	{/foreach}
	</table>
	{/if}
</div>
