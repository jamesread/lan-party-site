<?php

if (!Session::hasPriv('CONTENT_DELETE')) {
	throw new PermissionsException();
}

$id = intval($_REQUEST['id']);

$sql = 'DELETE FROM page_content WHERE id = :id';
$stmt = $db->prepare($sql);
$stmt->bindValue(':id', $id);
$stmt->execute();

redirect('listContent.php', 'Content deleted.');

?>
