<div style = "vertical-align: top;">
	{if $isMaintMode}
	<div class = "box">
		<p class = "bad">The site is down for maintenace, you will not be able to login.</p>
	</div>
	{/if}

	<div style = "width: 60%; float: left;">
		{include file = "form.tpl" excludebox = true}
	</div>

	<div class = "box" style = "width: 30%; float: left;">
		<h2>Forgot your password?</h2>
		<p>If you have forgotton your password, you can use the <a href = "forgotPassword.php">reset password form</a>.</a>
	</div>
</div>
