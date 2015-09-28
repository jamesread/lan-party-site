<div class = "box">
	<h2>User: {$user.username}</h2>

	<div style = "vertical-align: top;">
		<div style = "vertical-align: top; display: inline-block; ">
			<p><strong>Username: </strong>{$user.username}</p>
			<p><strong>Real name: </strong>{$user.realName}</p>
			<p><strong>Registered: </strong>{$user.registered}</p>
		</div>

		{if $user.canSeePrivate}
		<div style = "virtual-align: top; display: inline-block; ">
			<p><strong>Last login: </strong>{$user.lastLogin|default:"Never!"}</p>
			<p><strong>Email: </strong>{$user.email|default:'(none)'}</p>
			<p><strong>Ban status: </strong>{if $user.isBanned}<abbr title = "{$user.bannedReason}" class = "bad">Banned!</abbr>{else}No ban.{/if}</p>
		</div>
		{/if}

		<div style = "vertical-align: top; display: inline-block; float: right; text-align: right; ">
			{if isset($user.avatar)} 
				<img src = "{$user.avatar}" alt = "avatar" align = "right" />
			{else}
				<img src = "resources/images/defaultAvatar.png" alt = "avatar" align = "right" />
			{/if}
		</div>

	</div>

</div>
