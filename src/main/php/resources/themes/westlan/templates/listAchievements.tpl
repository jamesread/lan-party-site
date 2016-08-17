<div class = "box">
	<h2>Acheivements</h2>

	<style type = "text/css">
	{literal}
tr.subtle {
	color: gray;
}

tr.subtle img {
	opacity: .5;
}
{/literal}
	</style>

	<table>
	{foreach from = "$listAchievements" item = acheiv}
		{if $acheiv.earned}
		<tr>
		{else}
		<tr class = "subtle">
		{/if}
			<td><img width = "50" src = "{$acheiv.icon}" alt = "acheiv icon" /></td>
			<td><strong>{$acheiv.title}</strong></td>
			<td>{$acheiv.description}</td>
		</tr>
	{/foreach}
	</table>
</div>
