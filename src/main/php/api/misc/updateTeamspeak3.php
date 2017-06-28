<?php

require_once '../../includes/common.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$teamspeakStatus = file_get_contents('westlanTeamspeak3.json');
$teamspeakStatus = json_decode($teamspeakStatus, true);

if (((time() - $teamspeakStatus['lastUpdated']) >= 900) || isset($_REQUEST['update']))
{
	$host = getSiteSetting('plugin.teamspeak3.host');
	$port = getSiteSetting('plugin.teamspeak3.port');

	$content = file_get_contents('http://api.planetteamspeak.com/servernodes/'.$host.':'.$port.'/');
	$content = json_decode($content, true);

	$clients = 0;

	if (!isset($content['result']) || !isset($content['result']['data'])) {
		//It fails really regulary and pollutes the logs. 
		//logActivity('The teamspeak API failed during an update: ' . json_encode($content));
	} else {
		foreach ($content['result']['data'] as $object) {
			if ($object['class'] == 'client') {
				$clients++;
			}
		}
		
		$teamspeakStatus = array(
			'lastUpdated' => time(),
			'clients' => $clients,
			'content' => $content,
		);
		
		file_put_contents('westlanTeamspeak3.json', json_encode($teamspeakStatus));
		logActivity('Updated the Teamspeak stats successfully.');
	}
}

echo json_encode($teamspeakStatus);

?>

