<?php

require_once 'includes/common.php';
require_once 'includes/classes/FormCreateGallery.php';
require_once 'libAllure/FormHandler.php';

use \libAllure\FormHandler;

$handler = new FormHandler('FormCreateGallery');
$handler->setRedirect('listGalleries.php');;
$handler->handle();
