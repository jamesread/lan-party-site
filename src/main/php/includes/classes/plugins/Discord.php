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

class Discord implements Plugin {
	public function getSettingsForm() {
		return new FormPluginDiscordSettings();
	}

	private function shouldNotDisplay() {
		if (Session::hasPriv('ADMIN')) {
			$excludesPages = explode("\n", getSiteSetting('plugin.discord.ignorePages.admin'));
		} else {
			$excludesPages = explode("\n", getSiteSetting('plugin.discord.ignorePages'));
		}

		return in_array(basename($_SERVER['PHP_SELF']), $excludesPages);
	}

	public function renderSidebar() {
		if ($this->shouldNotDisplay()) {
			return;
		}

		startbox();
		echo '<div class="discordServerView"></div>';
		echo '<script type="text/javascript">
			function drawTree(response) {
				console.log(response);

				if (response.AFK !== undefined) {
					$.each(response, function( channelName, users ) {
						$(".discordServerView").append(addChannel(channelName, users));
					});
				} else {
					console.log(response);
				}
			}

			function addChannel(channelName, users) {
				var spacer = "<br />&nbsp;&nbsp";
				var html = $("<div />");

				html.append($("<span />").text(channelName));

				$.each(users, function( i, user ) {
					html.append($("<span />").html(spacer + user));
				});

				return html;
			}

			$(document).ready(function(){
				$.ajax({
					url: "' . getSiteSetting('plugin.discord.uri') . '",
					dataType: "jsonp",
					crossDomain: true,
					success: function(data) {
						drawTree(data);
					}
				});
			});
		</script>';

		#echo '<script type = "text/javascript"></script>';
		echo '<div class = "server">';
		echo '<br /><p>Discord is awesome and way better than Skype. <a target="_blank" href="https://discordapp.com/">Use Discord</a>.';
		echo '<p><small><strong>Join the Fun</strong>: <a target="_blank" href="https://discord.gg/' . getSiteSetting('plugin.discord.invite') . '">Invite Yourself In</a>.</small></p>';
		echo '</div>';

		stopbox('Discord');
	}
}

class FormPluginDiscordSettings extends Form {
	public function __construct() {
		parent::__construct('discordSettings', 'Discord settings');

		$this->addElement(new ElementTextbox('excludePages', 'Exclude pages', getSiteSetting('plugin.discord.ignorePages')));
		$this->addElement(new ElementTextbox('excludePagesAdmin', 'Exclude pages as admin', getSiteSetting('plugin.discord.ignorePages.admin')));
		$this->addElement(new ElementInput('discordUri', 'Discord Endpoint URI', getSiteSetting('plugin.discord.uri')));
		$this->addElement(new ElementInput('discordInvite', 'Discord Invite Code', getSiteSetting('plugin.discord.invite')));
		$this->addDefaultButtons();
	}

	public function process() {
		setSiteSetting('plugin.discord.ignorePages', $this->getElementValue('excludePages'));
		setSiteSetting('plugin.discord.ignorePages.admin', $this->getElementValue('excludePagesAdmin'));
		setSiteSetting('plugin.discord.uri', $this->getElementValue('discordUri'));
		setSiteSetting('plugin.discord.invite', $this->getElementValue('discordInvite'));
	}
}
?>
