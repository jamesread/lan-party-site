<div class = "box">
	<h2>{$title|default:"Notification"}</h2>
	<p>{$message|default:"No notification provided."}</p>

	{if isset($links) and $links->hasLinks()}
	<div>
		<dl>
		{foreach from = "$links" item = "link"}
			<dt class = "{$link.containerClass}"><a href = "{$link.url}">{$link.title}</a></dt>
		{/foreach}
		</dl>
	</div>
	{/if}
</div>
