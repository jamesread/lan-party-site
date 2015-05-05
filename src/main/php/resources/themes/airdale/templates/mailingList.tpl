<div class = "box">
	<h2>Mailing List</h2>

	{if $isEventSpecific} 
	<p>This is the email address for everyone signed up to the event. It ignores their mailing list opt in/out.</p>
	{else}
	<p>This is the general mailing list.</p>	
	{/if}
	
	{if $mailingListRecipients|@count == 0}
		<p>Nobody is on this mailing list!</p>
	{else}
		<textarea rows = "4" cols = "80" style = "width: 100%;">{', '|implode:$mailingListRecipients}</textarea>
	{/if}

	<h3>General Mailing List</h3>
	<p>You can reload the general mailing list by <a href = "viewMailingList.php">clicking here</a>.</p>

	{include file = "form.tpl" excludeBox = "yes"}

</div>
