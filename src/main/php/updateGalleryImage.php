<?php

require_once 'includes/common.php';
require_once 'libAllure/Inflector.php';

use \libAllure\Sanitizer;
use \libAllure\FormHandler;
use \libAllure\Inflector;

$sanitizer = new Sanitizer();

$gallery = $sanitizer->filterUint('gallery');
$filename = $sanitizer->filterString('filename');

$handler = new FormHandler('FormGalleryImageEdit', $tpl);
$handler->setConstructorArgument(0, $gallery);
$handler->setConstructorArgument(1, $filename);
$handler->setRedirect('viewGalleryImage.php?gallery=' . $gallery . '&amp;filename=' . $filename, 'Gallery image edited.');
$handler->handle();

?>
