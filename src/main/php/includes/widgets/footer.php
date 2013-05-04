<?php

// Clear any  buffers, which hopefully don't exist by now.
flushOutputBuffers();

global $tpl, $db;

$tpl->assign('queryCount', $db->queryCount);
$tpl->assign('randNum', rand(0, 10));
$tpl->assign('copyright', getSiteSetting('copyright'));

$stuff = array('new processes processed', 'donuts eaten', 'flying waffles avoided', 'spatial anonymity identified', 'whales harpooned', 'umpa lumpas killed', 'monkeys utilized', 'meatballs neutralized', 'hard disks burnt', 'spelling mistakes', 'UFOs identified', 'burnt pizzas', 'processors used', 'admins interfered', 'more minecraft players identified');
$tpl->assign('randTxt', $stuff[array_rand($stuff)]);
$tpl->display('footer.tpl');

exit;

?>
