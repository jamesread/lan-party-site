<script type = "text/javascript" src = "resources/javascript/seatingPlan.js"></script>

<div class = "box">
<style type = "text/css">
{literal}
.spBlock {
	background-color: black;
	display: table-cell;
	width: 25px;
	height: 25px;
	border: 1px solid white;
	text-align: center; vertical-align: middle;
}

.spInvisible {
	background-color: white;
}

.spSeat:hover {
	border: 1px solid black;
}

.spSeat {
	background-color: orange;
}

.spLabel {
	color: white;
	position: relative;
	white-space: nowrap;
	padding-left: .5em;
	padding-right: .5em;
}

div.seatingContainer a {
	color: white !important;
}

.spSelected {
	background-color: blue;
}

.spSelf {
	background-color: green;
}

div.seatingContainer {
	text-align: center;
	padding: 1em;
	margin: auto;
	border: 1px solid black;
	background-color: white;
	display: inline-block;
	vertical-align: top;
}
{/literal}
</style>
{if !empty($itemSeatingPlan.eventName)}
	<h2><a href = "seatingplan.php?event={$itemSeatingPlan.event}">Seating plan for: {$itemSeatingPlan.eventName}</a></h2>
	<p>Hover your mouse over a seat to see who is sitting there, or use the long list of attendees below the diagram.</p>
{else}
	<h2><a href = "listSeatingPlans.php">Seating plan</a> &raquo; <strong>{$itemSeatingPlan.name}</strong></h2>
	<p>This is a seating plan. It can be used in <a href = "listEvents.php">events</a> by selecting an event, and updating the event "seating plan" option.<p>
	<p><a href = "updateSeatingPlan.php?id={$itemSeatingPlan.id}">Update seating plan</a><p>
{/if}

	<div class = "seatingContainer">
	{foreach from = $listSeatingPlanObjects item = "itemRow"}
		<div class = "seatingRow">
		{foreach from = $itemRow item = "itemObject"}
			{if $itemObject.type == "seat"}
				{if empty($itemSeatingPlan.event)}
				<span class = "spBlock spSeat">{$itemObject.index}</span>
				{else}
				<a id = "seat{$itemObject.index|intval}" href = "#selectSeat" onclick = "selectSeat({$itemSeatingPlan.event},{$itemObject.index})" class = "spBlock spSeat">{$itemObject.index}</a>
				{/if}
			{elseif $itemObject.type == "block"}
				<span class = "spBlock">&nbsp;</span>
			{elseif $itemObject.type == "break"}
				<br />
			{elseif $itemObject.type == "label"}
				<span class = "spBlock spLabel">&nbsp;{$itemObject.text}</span>
			{else}
				<span class = "spBlock spInvisible">&nbsp;</span>
			{/if}
		{/foreach}
		</div>
	{/foreach}
	</div>

	<div style = "display: inline-block; vertical-align: top; margin-left: 5em; ">
		<strong>Key:</strong>
		<p><span style = "display: inline-block" class = "spBlock spSeat">&nbsp;</span>Available seat</p>
		<p><span style = "display: inline-block" class = "spBlock spSeat spSelected">&nbsp;</span>Selected by someone else</p>
		<p><span style = "display: inline-block" class = "spBlock spSeat spSelf">&nbsp;</span>You!</p>
		<p><span style = "display: inline-block" class = "spBlock">&nbsp;</span>Something else (eg: projectors, servers)</p>
	</div>
</div>

{if isset($itemSeatingPlan.eventName)}
<div class = "box" style = "columns: 100px 3; -webkit-columns: 100px 3; -o-columns: 100px 3; -moz-columns: 100px 3">
	<h2>List of people</h2>

	{section name = "seatLabel" loop = $itemSeatingPlan.seatCount}
		<p>Seat {$smarty.section.seatLabel.iteration}: <span id = "seat{$smarty.section.seatLabel.iteration}label">empty</span></p>
	{/section}
</div>
<script type = "text/javascript">
loadInitialSeats({$itemSeatingPlan.event});
</script>
{/if}
