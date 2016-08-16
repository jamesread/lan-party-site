<?php

require_once 'includes/common.php';

if (count($_FILES) > 0) {
	var_dump($_FILES);
	exit;
}

require_once 'includes/widgets/header.php';

?>
<style type = "text/css">
form.dropzone {
	border: 1px dotted black;
	padding: 2em;
}

.dz-image-preview {
	display: inline-block;
	border: 1px solid blue;
	text-align: center;
	padding: 1em;
}
</style>
<script type = "text/javascript" src = "resources/javascript/dropzone.js"></script>
<div class = "box">
<h2>Upload with HTML5</h2>
<p>Some browsers and operating systems support upload via drag and drop. Try dragging files from your desktop file explorer directly in to the browser. If this does not seem to work, try the <a href = "formUploadImage.php">form based uploader</a>.</p>
<form class = "dropzone" action = "html5uploadImage.php" id = "uploadImage">
	<div class = "dz-message">Drag and drop your your images here...</div>
</form>
</div>
<?php

require_once 'includes/widgets/footer.php';

?>
