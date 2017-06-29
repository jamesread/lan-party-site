<div class = "promoBanner">
	<div style = "flex: 1 1 auto;">
		<h1><a href = "viewEvent.php?id={$nextEvent.id}">{$nextEvent.name}</a></h1>
		<p>{$nextEvent.date}, at {$nextEvent.venue}. <strong>{$signups.signups}</strong> people signed up!</p>
	</div>


	<div class = "fakeButtonLinks">
		{$signupLinks|default:""}<br />
	</div>
</div>
