<?php

require_once '../../includes/common.php';
require_once 'libAllure/Sanitizer.php';

$san = new \libAllure\Sanitizer();

$event = $san->filterUint('event');

$seats = file_get_contents('http://westlan.co.uk/api/json/seatingPlanListSeats.php?eventId=' . $event, true);
$seats = json_decode($seats);

function keySeats($seats) {
	$ret = array();

	foreach ($seats as $seat) {
		$ret[$seat->seat] = $seat;
	}

	$ret['length'] = sizeof($seats);

	return $ret;
}

//$seats = keySeats($seats);
$seats = json_encode($seats);

?>

<html>
<style type = "text/css">
.username {
	font-size: 90;
	font-weight: bold;
}

.seat {
	font-size: 70;
}

p {
	color: blue;
	text-align: center;
	font-family: Verdana;
}

#winner {
	color: red;
	display: none;
	font-weight: bold;
	font-size: 50;
	text-decoration: blink;
}
</style>

<p id = "ui">script not running, possible no seats were found or you didn't pass a correct ?eventId=xxx argument;</p>
<p id = "winner">
	<img src = "fireworks.gif" />
	WINNER!
	<img src = "fireworks.gif" />
</p>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type = "text/javascript">
window.seats = <?= $seats; ?>;
</script>
<script type = "text/javascript">
var ticks = 0;

function sleep(millis, callback) {
    setTimeout(function()
            { callback(); }
    , millis);
}

function tick() {
	rnd = Math.floor(Math.random() * window.seats.length);
	randomChoice = window.seats[rnd];

	if (randomChoice['username'] == null) {
		randomChoice['username'] = "anbody sitting here?!";
	}

	window.ticks++;
	$('#ui').html('<span class = "seat">Seat: '+ randomChoice['seat']+'</span><br/><span class = "username">' + randomChoice['username'] + '</span>')

	if (window.ticks < 20) {
		sleep(150, tick);
	} else {
		if (randomChoice['seatCss'].indexOf("maroon") > -1) {
			$('#winner').html("Oh, but you're staff, you win nothing bro! Try again...");
		}

		$('#winner').css('display', 'block');
	}
}


tick();
</script>
</html>
