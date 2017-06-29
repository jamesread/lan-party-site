<div class = "box">
	<h2>Signups</h2>
	{if $signups|@count eq 0}
		<p>Nobody has signed up yet. :(</p>
	{else}
		<table>
			<tr>
				<th>Username{if $privViewAttendance}<br /><small>Attendance</small>{/if}</th>
				<th>Real Name</th>
				<th>Status{if $privViewSignupComments}<br /><small>User requirement</small>{/if}</th>
				{if $IS_LOGGED_IN}
				<th>Actions</th>
				{/if}
			</tr>

			{foreach from = $signups item = "signup"}
				<tr>
					<td>
						{if $privViewSignupComments and ($signup.status == "SIGNEDUP" OR $signup.status == "CANCELLED")}
							<a class = "unpublished" href = "profile.php?id={$signup.user}" style = "{$signup.userGroupCss}">{$signup.username}</a>
						{else}
							<a href = "profile.php?id={$signup.user}" style = "{$signup.userGroupCss}">{$signup.username}</a>
						{/if}
						{if $privViewAttendance}
							<br />
							<small>
							{if $signup.countAttended == 0 and $signup.countCancelled == 0}
									<strong class = "good">newbie!</strong>
							{else}
								<a href = "viewAttendance.php?user={$signup.user}">
								{$signup.countAttended}
								<strong>{section name = countCancelled loop = $signup.countCancelled start = 0}C{/section}</strong>
							{/if}
							</a>
							<br /><br />
							{if $signup.status == "PAID" or $signup.status == "STAFF" or $signup.status == "ATTENDED"}
							<a href = "seatingplan.php?event={$signup.event}">{if $signup.selectedSeat == null}<em class = "bad">no seat selected!</em>{else}Seat {$signup.selectedSeat}{/if}</a>
							{/if}
							</small>
						{/if}
					</td>
					<td>{$signup.userRealName}</td>
					<td>
					{$signup.status}
						{if $privViewSignupComments}
							{if isset($signup.ticketCost)}
								- {$signup.ticketCost|doubleToGbp}<br />

								{if not empty($signup.comments)}
								<small>{$signup.comments|htmlify:2}</small>
								{/if}
							{/if}
						{/if}
					</td>

					{if $IS_LOGGED_IN}
					<td>{$signup.actions}</td>
					{/if}
				</tr>
			{/foreach}
		</table>
	{/if}
	<p>A total of <strong>{$signupStatistics.signups}</strong> signups, <strong>{$signupStatistics.paid}</strong> paid.</p>

		<div style = "margin-top: 1em;">
		<div style = "width: 40%; display: inline-block; vertical-align: top;">
		
		{if isset($form)}
			{include file = "form.tpl" excludeBox = "no"}
		{/if}


		{if isset($eventFinanceOverview)}
		</div>
		<div style = "width: 40%; display: inline-block; vertical-align: top;">
		<h3>Event finances</h3>
		<table>
			<thead>
				<tr>
					<th>Monnies received</th>
					<th>Amount</th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<td><abbr title = "STAFF + PAID + ATTENDED">Paid:</abbr> </td>
					<td>{$eventFinanceOverview.paid|number_format:2}</td>
				</tr>

				<tr>
					<td><abbr title = "SIGNEDUP">Signed Up:</abbr> </td>
					<td>{$eventFinanceOverview.signedup|number_format:2}</td>
				</tr>

				<tr>
					<td><abbr title = "All other statuses with a ticket cost">Unaccounted:</abbr> </td>
					<td>{$eventFinanceOverview.unaccounted|number_format:2}</td>
				</tr>
			</tbody>
		</table>
		{/if}
		</div>
		</div>
</div>
