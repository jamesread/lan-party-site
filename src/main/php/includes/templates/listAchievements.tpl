<h2>Avail</h2>
{foreach from = $listAchievements item = achiev}
	<a href = "updateAchievement.php?id={$achiev.id}">{$achiev.title}</a><br />
{/foreach}

<h2>Earnt</h2>
{foreach from = $listEarnt item = earnt}
	{$earnt.username} = {$earnt.title}: {$earnt.description}<br />
{/foreach}
