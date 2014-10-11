<div class = "box">
	<h2>Galleries</h2>

	{$galleryIntro}

	<div class = "photoList">
		{foreach from = "$listGalleries" item = "itemGallery"}
		<div class = "photoGallery">
			<a {if $itemGallery.status != "Open"}class = "unpublished"{/if} href = "viewGallery.php?id={$itemGallery.id}">
				{if empty($itemGallery.coverImage)}
				<img src = "resources/images/defaultGallery.png" alt = "gallery image" />
				{else}
				<img src = "{$itemGallery.thumbPath}{$itemGallery.coverImage}" alt = "gallery image" />
				{/if}

				<br />{$itemGallery.title}
			</a>
		</div>
		{/foreach}
	</div>
</div>
