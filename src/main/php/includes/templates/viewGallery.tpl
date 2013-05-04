<div class = "box">
	<h2><a href = "listGalleries.php">Galleries</a> &raquo; Gallery <a href = "viewGallery.php?id={$gallery.id}">{$gallery.title}</a></h2>

	{if not empty($event)}
	<p>These are the gallery images from <strong><a href = "viewEvent.php?id={$event.id}">{$event.name}</a></strong>, which was on <strong>{$event.date}</strong>.</p>
	{/if}

	<div class = "photoGallery">
	{if empty($gallery.description)}
		<p>{$gallery.description}<p>
	{/if}

	{if count($files) eq 0}
		<p>There are no files in this gallery.</p>
	{else}
		<p>There are <strong>{$files|@count}</strong> image(s) in this gallery.</p>

		{foreach from = $files item = photo}
		{if $photo.published eq false}
			{if $privViewUnpublished}
			<a href = "viewGalleryImage.php?filename={$photo.filename}&amp;gallery={$gallery.id}" class = "unpublished">
				<img width = "100" src = "resources/images/galleries/{$gallery.folderName}/thumb/{$photo.filename}" class = "galleryImage" alt = "Gallery Image" />
			</a>
			{/if}
		{else}
			<a href = "viewGalleryImage.php?filename={$photo.filename}&amp;gallery={$gallery.id}">
				<img width = "100" src = "resources/images/galleries/{$gallery.folderName}/thumb/{$photo.filename}" class = "galleryImage" alt = "Gallery Image" />
			</a>
		{/if}
		{/foreach}
	{/if}
	</div>
</div>
