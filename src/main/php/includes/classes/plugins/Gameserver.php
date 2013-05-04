<?php

require_once 'includes/classes/Plugin.php';

class Gameserver implements Plugin {
	public function getSettingsForm() {}
	public function renderSidebar() {
		startbox();
		echo <<<MARKUP
<img src = "http://www.westlan.co.uk/resources/images/wiki/tf2large.png" alt = "TF2" style = "float: right" />
<p>players: <span id = "playersInServer">#</span>/<span id = "playersMax">#</span></p>
<p>map: <span id = "mapName">{map name}</span></p>
<p>
<a href = "steam://connect/tf2.westlan.co.uk:27015">tf2.westlan.co.uk</a>
<br />
<span class = "unpublished"> (click to connect)</span>
</p>
MARKUP;
		echo <<<JS
<script type = "text/javascript">
$.getJSON('api/json/game2.redphase.info.php', function(gameServer) {
	$('#playersInServer').text(gameServer.playersInServer);
	$('#playersMax').text(gameServer.playerSlots);
	$('#mapName').text(gameServer.mapName);
});
</script>
JS;
		stopbox('WestLAN TF2 Server');
	}
}

?>
