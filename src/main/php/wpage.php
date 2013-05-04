<?php

require_once 'includes/common.php';

$content = getContent($_REQUEST['title']);

require_once 'includes/widgets/header.php';
require_once 'includes/widgets/sidebar.php';

startBox();
echo $content;
stopBox($_REQUEST['title']);

require_once 'includes/widgets/footer.php';

?>
