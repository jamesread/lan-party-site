

function refresh(struct) {
	$('.server').empty();

	var htmlServerTitle = $('<strong />');
	htmlServerTitle.css('font-weight', 'bold');
	htmlServerTitle.text(struct.name);

	var htmlChannelList = $('<ul class = "channelList" />');

	$('.server').append(htmlServerTitle);
	$('.server').append(htmlChannelList);

	console.log(struct);

	refreshChannel(htmlChannelList, struct.root)
}

function refreshChannel(par, container) {
	$(container.channels).each(function(index, channel) {
		var htmlChannel = $('<li class = "channel" />');
		htmlChannel.text(channel.name);

		par.append(htmlChannel);

		refreshUsers(htmlChannel, channel);


		if ($(channel.channels).size() > 0) {
			var htmlChannelList = $('<ul class = "channelList" />');

			refreshChannel(htmlChannelList, channel);

			htmlChannel.append(htmlChannelList);
		}

	});

}

function refreshUsers(htmlChannel, channel) {
	var htmlUserlist = $('<ul class = "userlist">');

	if ($(channel.users).size() > 0) {
		$(channel.users).each(function(index, user) {
			var htmlUser = $('<li class = "user">');
			htmlUser.text(user.name);

			htmlUserlist.append(htmlUser);
		});

		htmlChannel.append(htmlUserlist);
	}
}

var struct = $.getJSON('http://tydus.net/MumPI/?view=json&serverId=1', {}, refresh);