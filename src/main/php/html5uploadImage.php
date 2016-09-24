<?php

require_once 'includes/common.php';
require_once 'includes/classes/Galleries.php';

$gallery = Galleries::getById($_REQUEST['gallery']);

if (!isset($_REQUEST['gallery'])) {
	redirect('listGalleries.php', 'Gallery not specified.');
}

if (count($_FILES) > 0) {
	function validateImageUpload() {
		if (!@is_uploaded_file($_FILES['file']['tmp_name'])) {
			throw new Exception('Got an object which is not a file.');
		}

		if (!@getimagesize($_FILES['file']['tmp_name'])) {
			throw new Exception('Cannot interpret that as an image.');
		} 
	}

	function moveFileToTemp() {
		$tempName = tempnam('tempUploads', uniqid());
		$mov = @move_uploaded_file($_FILES['file']['tmp_name'], $tempName);

		if (!$mov) {
			throw new Exception('Could not move uploaded file: ' . $tempName);
		}

		return $tempName;
	}

	function validateImage($filename, $maxW, $maxH) {
		$type = exif_imagetype($filename);

		if ($type == IMAGETYPE_JPEG) {
			$imageResource = imagecreatefromjpeg($filename);
		} else if ($type == IMAGETYPE_GIF) {
			$imageResource = imagecreatefromgif($filename);
		} else if ($type == IMAGETYPE_PNG) {
			$imageResource = imagecreatefrompng($filename);
		} else {
			throw new Exception("Unsupported file type");
			return;
		}

		if (imagesx($imageResource) > $maxW || imagesy($imageResource) > $maxH) {
			throw new Exception('Image too big, images may up to ' . $maxW . 'x' . $maxH . ' pixels, that was ' . imagesx($imageResource) . 'x' . imagesy($imageResource) . ' pixels.');
		}

		return $imageResource;
	}


	try {
		validateImageUpload();
		$filename = moveFileToTemp();
		$imageResource = validateImage($filename, 1280, 1024);

		$destinationFilename = sha1_file($filename) . '.png';

		imagepng($imageResource, $gallery->getFullPath() . $destinationFilename);
		imagepng($imageResource, $gallery->getThumbPath() . $destinationFilename);


		logActivity("Image uploaded: " . $destinationFilename . ' into gallery ID ' . $gallery->getId() . ' (' . $gallery->getTitle() . ')');
		createGalleryDbEntry($destinationFilename, $gallery->getId());
		echo $destinationFilename;
	} catch (Exception $e) {
		logActivity("Failed to upload image:" . $e->getMessage());
		header("HTTP/1.0 403 Forbidden"); 
		echo $e->getMessage();
		exit;
	}

	exit;
}

require_once 'includes/widgets/header.php';

?>
<style type = "text/css">
form.dropzone {
	border: 1px dotted black;
	padding: 2em;
}

form.dropzone:hover {
	background-color: lightgray;
	cursor: pointer;
}

form.dropzone.dz-drag-hover {
	background-color: lightgray;
}

.dz-image-preview {
	display: inline-block;
	border: 1px solid blue;
	text-align: center;
	padding: 1em;
}

.dz-processing {
	background-color: orange;
}

.dz-success {
	background-color: LimeGreen;
}

.dz-error-message {
	background-color: red;
}

.dz-error-mark, .dz-success-mark {
	display: none;
}
</style>
<script type = "text/javascript" src = "resources/javascript/dropzone.js"></script>
<div class = "box">
<h2>Upload images to the <?php echo $gallery->getTitle(); ?> gallery</h2>
<p>Some browsers and operating systems support upload via drag and drop. Try dragging files from your desktop file explorer directly in to the browser. If this does not seem to work, try the <a href = "formUploadImage.php">form based uploader</a>.</p>
<form class = "dropzone" action = "html5uploadImage.php" id = "uploadImage">
	<div class = "dz-message">Drag and drop your your images here...</div>
	<input type = "hidden" name = "gallery" id = "gallery" value = "<?php echo $gallery->getId(); ?>" />
</form>
	<p><strong>Note:</strong> Your images need to be <em>approved</em> before you'll see them in the public galleries.</p>
</div>
<?php

require_once 'includes/widgets/footer.php';

?>
