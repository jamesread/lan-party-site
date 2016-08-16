<?php

require_once 'includes/classes/Events.php';
require_once 'includes/classes/Plugin.php';

require_once 'libAllure/Form.php';

use \libAllure\Form;
use \libAllure\Inflector;
use \libAllure\ElementTextbox;
use \libAllure\ElementInput;
use \libAllure\ElementAlphaNumeric;
use \libAllure\ElementNumeric;
use \libAllure\Session;

class Teamspeak3 implements Plugin {
	public function getSettingsForm() {
		return new FormPluginTeamspeak3Settings();
	}

	private function shouldNotDisplay() {
		if (Session::hasPriv('ADMIN')) {
			$excludesPages = explode("\n", getSiteSetting('plugin.teamspeak3.ignorePages.admin'));
		} else {
			$excludesPages = explode("\n", getSiteSetting('plugin.teamspeak3.ignorePages'));
		}

		return in_array(basename($_SERVER['PHP_SELF']), $excludesPages);
	}

	public function renderSidebar() {
		if ($this->shouldNotDisplay()) {
			return;
		}

		startbox();
		echo '<div class="teamspeak3ServerView"></div>';
		echo '<script type="text/javascript">
				function drawTree(data) {
					data = JSON.parse(data);
					if (data !== undefined && data !== "") {
						if (data.result !== undefined && data.result.data !== undefined) {
							console.log(data.result.data);
							$.each(data.result.data, function( i, row ) { addRow(i, row, data.result.data); });
						}
					}
					console.log($(".teamspeak3ServerView"));
				}
				
				function addRow(i, row, allRows) {
					var parent = $("." + row.parent);
					var spacer = "&nbsp;&nbsp;";
					
					console.log(row.parent);
					
					if (row.class !== "server") {
						// allRows[0] should always be the top level server object
						if (row.parent === allRows[0].ident) {
							console.log("top-level channel");
							parent = $(".teamspeak3ServerView");
						}
						
						var html = $("<div class=\"" + row.ident + "\" />");
						
						if (row.class === "channel") {
							html.addClass("channel");
							html.append($("<img src=\"http://tydus.net/MumPI/viewer/resources/images/channel_12.png\" alt=\"channel icon\" />"));
						}
						else if (row.class === "client") {
							html.addClass("user");
							html.append($("<img src=\"http://tydus.net/MumPI/viewer/resources/images/talking_off_12.png\" alt=\"channel icon\" />"));
						}
						
						for (var i = 2; i < row.level; i++) {
							html.prepend(spacer);
						}
						
						html.append("&nbsp;");
						html.append($("<span/>").text(row.name));
						
						parent.append(html);
					}
				}
				
				$(document).ready(function(){
					$.ajax({
						dataType: "json",
						type: "POST",
						url: "/api/misc/updateTeamspeak3.php",
						data: {
							host: "'.getSiteSetting('plugin.teamspeak3.host').'",
							port: "'.getSiteSetting('plugin.teamspeak3.port').'",
						},
						success: drawTree
					});
					/*$.get("https://api.planetteamspeak.com/servernodes/' . getSiteSetting('plugin.teamspeak3.host') . ':' . getSiteSetting('plugin.teamspeak3.port') . '/", null, drawTree);*/
				});
			</script>';
		#echo '<script type = "text/javascript"></script>';
		echo '<div class = "server">';
		echo '<br /><p>Teamspeak is a low-latency, high quality VOIP application created primarily for gamers. <a href="https://www.teamspeak.com/downloads">Download Teamspeak3</a>.';
		echo '<p><small><strong>Server address/label</strong>: ' . getSiteSetting('plugin.teamspeak3.host') . '<br /><strong>Port:</strong> ' . getSiteSetting('plugin.teamspeak3.port') . '</small></p>';
		echo '</div>';

		stopbox('Teamspeak 3');
	}
}

class FormPluginTeamspeak3Settings extends Form {
	public function __construct() {
		parent::__construct('teamspeak3Settings', 'Teamspeak 3 settings');

		$this->addElement(new ElementTextbox('excludePages', 'Exclude pages', getSiteSetting('plugin.teamspeak3.ignorePages')));
		$this->addElement(new ElementTextbox('excludePagesAdmin', 'Exclude pages as admin', getSiteSetting('plugin.teamspeak3.ignorePages.admin')));
		$this->addElement(new ElementInput('teamspeak3Host', 'Teamspeak Host Name', getSiteSetting('plugin.teamspeak3.host')));
		$this->addElement(new ElementNumeric('teamspeak3Port', 'Teamspeak 3 Port', getSiteSetting('plugin.teamspeak3.port')));
		$this->addDefaultButtons();
	}

	public function process() {
		setSiteSetting('plugin.teamspeak3.ignorePages', $this->getElementValue('excludePages'));
		setSiteSetting('plugin.teamspeak3.ignorePages.admin', $this->getElementValue('excludePagesAdmin'));
		setSiteSetting('plugin.teamspeak3.host', $this->getElementValue('teamspeak3Host'));
		setSiteSetting('plugin.teamspeak3.port', $this->getElementValue('teamspeak3Port'));
	}
}



?>
