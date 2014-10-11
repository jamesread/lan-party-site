<div class = "box">
	{if isset($links)}
		<h2>{$links->getTitle()}</h2>

		<div>
		{if $links->hasLinks()}
			<dl>
			{foreach from = "$links" item = "link"}
				<dt class = "{$link.containerClass}"><a href = "{$link.url}">{$link.title}</a></dt>
			{/foreach}
			</dl>
		{else}
			No links.
		{/if}
		</div>
	{else}
	<p class = "formValidationError">No links assigned.</p>
	{/if}
</div>
