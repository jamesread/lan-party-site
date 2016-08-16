<?php

header('Content-Type: application/json');

$current = file_get_contents("westlanTeamspeak3.json");
$current = json_decode($current, true);
$content;

if ((time() - $current['lastUpdated']) >= 900)
{
	$host = stripslashes($_POST['host']);
	$port = isset($_POST['port']) ? $_POST['port'] : 9987;
	
	$content = file_get_contents('http://api.planetteamspeak.com/servernodes/'.$host.':'.$port.'/');
	
	$json = array(
		'lastUpdated' => time(),
		'content' => $content,
	);
	
	$json = json_encode($json);

	file_put_contents("westlanTeamspeak3.json", $json);
}
else
{
	$content = $current['content'];
}

echo json_encode($content);

?>