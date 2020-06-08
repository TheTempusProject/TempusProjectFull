<h2>Outbox</h2>
{PAGINATION}
<form action="{BASE}usercp/messages/delete" method="post">
	<table class="table table-hover">
		<thead>
			<tr>
				<th style="width: 20%">To</th>
				<th style="width: 40%">Subject</th>
				<th style="width: 20%">Sent</th>
				<th style="width: 10%"></th>
				<th style="width: 10%">
					<INPUT type="checkbox" onchange="checkAll(this)" name="check.e" value="F_[]"/>
				</th>
			</tr>
		</thead>
		<tbody>
			{LOOP}
			<tr>
				<td><a href="{BASE}home/profile/{userTo}">{userTo}</a></td>
				<td><a href="{BASE}usercp/messages/viewmessage/{ID}">{subject}</a></td>
				<td>{DTC date}{sent}{/DTC}</td>
				<td><a href="{BASE}usercp/messages/delete/{ID}">Delete</a></td>
				<td>
					<input type="checkbox" value="{ID}" name="F_[]">
				</td>
			</tr>
			{/LOOP}
			{ALT}
			<tr>
				<td align="center" colspan="6">
					No Messages
				</td>
			</tr>
			{/ALT}
		</tbody>
	</table>
	<button name="submit" value="submit" type="submit" class="btn btn-sm btn-danger">Delete</button>
</form>