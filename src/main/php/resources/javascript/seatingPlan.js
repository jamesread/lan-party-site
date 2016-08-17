"use strict"; 

function selectSeat(eventId, seatId) {
	try {
		$.ajax({
			type: 'POST',
			url: 'api/json/seatingPlanSelectSeat.php?event=' + eventId + '&seat=' + seatId,
			success: onSeatUpdate,
			dataType: 'json' 
		});

	} catch (err) {
		notification('Error selecting seat:' + err);
	}
} 

function onSeatUpdate(recv) {
	window.alert(recv.message);

	$(recv.seatChanges).each(function(index, seatChange) {
		renderSeatChange(seatChange);
	});
}

function onLoadInitialSeats(recv) {
	$(recv).each(function(index, seatChange) {
		renderSeatChange(seatChange);
	});
}

function renderSeatChange(seatChange) {
	var seat = $('#seat' + seatChange.seat);
	var seatLabel = $('#seat' + seatChange.seat + 'label');

	if (seatChange.type == "delete") {
		seat.attr('title', '');
		seat.css('background-color', 'orange');
		seatLabel.html('');
	} else {

		if (seatChange.username == "self") {
			seat.addClass('spSelected spSelf');
			seat.attr('title', 'You!');
			seatLabel.html('<strong><u>You!</u></strong>');
		} else {
			seat.addClass('spSelected');
			seat.attr('title', seatChange.username);
			seatLabel.html('<strong>' + seatChange.username + '</strong>');

			if (typeof(seatChange.usernameCss) != "undefined") {
				seatLabel.attr('style', seatChange.usernameCss)
			}

			if (typeof(seatChange.seatCss) != "undefined") {
				seat.attr('style', seatChange.seatCss);
			}
		}
	}
}

function loadInitialSeats(eventId) {
	try {
		$.ajax({
			type: 'POST',
			url: 'api/json/seatingPlanListSeats.php?event=' + eventId,
			success: onLoadInitialSeats,
			dataType: 'json',
		});
	} catch (err) {
	}
}
