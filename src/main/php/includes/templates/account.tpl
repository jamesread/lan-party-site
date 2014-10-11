<div class = "box">
    <h2>Account: {$username}</h2>

	{if $emailFlagged}
	<p class = "bad">Your email address has been marked as invalid by an administrator, please use the update profile form to update it.</p>
	{/if}

	{foreach from = $notifications item = "notification"}
		<p class = "bad">{$notification}</p>
	{/foreach}

    <p>Below are a list of things you can do with your account.</p>

    <dl>
    {foreach from = "$standardLinks" item = "link"}
       <dt class = "{$link.iconUrl}"><a href = "{$link.url}">{$link.title}</a></dt>
    {/foreach}
        <dt class = "profile"><a href = "profile.php">View my profile</a></dt>
        <dt class = "profile"><a href = "users.php?action=edit">Update my profile</a></dt>
    </dl>

{if $privilegedLinks->hasLinks()}
    <h3>Privileges</h3>

    <p>Most of the admin functionality is built inline with the content. For everything else, there's mastercard... Oh, I mean this menu.</p>

    <dl>
    {foreach from = "$privilegedLinks" item = "link"}
        <dt class = "{$link.iconUrl}"><a href = "{$link.url}">{$link.title}</a></dt>
    {/foreach}
    </dl>
{/if}
</div>
