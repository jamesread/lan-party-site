<div class = "box">
	<h2><a href = "listGalleries.php">Galleries</a> &raquo; Gallery: <a href = "viewGallery.php?id={$gallery.id}">{$gallery.title}</a> &raquo; Image: <a href = "viewGalleryImage.php?filename={$image.filename}&amp;gallery={$gallery.id}">{$image.filename}</a></h2>

	<div style = "text-align: center;">
		{if not empty($image.caption)}
		<p>Caption: {$image.caption}</p>
		{/if}
		<p>&laquo;
		{if empty($prevFilename)}
			Prev
		{else}
			<a href = "viewGalleryImage.php?filename={$prevFilename.filename}&gallery={$gallery.id}">Prev</a>
		{/if}
		|
		{if empty($nextFilename)}
			Next
		{else}
			<a href = "viewGalleryImage.php?filename={$nextFilename.filename}&gallery={$gallery.id}">Next</a>
		{/if}
		&raquo;</p>
		<p>Image <strong>{$imageNumber}</strong> of <strong>{$imageCount}</strong> in this gallery</p>

		{if not $image.published}
		<p class = "bad">This image is not published.</p>
		{/if}
	</div>

	<br /><br />

	<div class = "galleryImage">
		<div class = "bigImageContainer">
			{if not empty($nextFilename)}
				<a href = "viewGalleryImage.php?filename={$nextFilename.filename}&gallery={$gallery.id}" title = "Click for next image">
			{/if}
			<img class = "galleryImage" src = "resources/images/galleries/{$gallery.folderName}/full/{$image.filename}" alt = "bigImage" />
			{if not empty($nextFilename)}
				</a>
			{/if}
		</div>
	</div>
</div>
