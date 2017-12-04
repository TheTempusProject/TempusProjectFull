<legend>Subscribers</legend>
{PAGINATION}
<form action="{BASE}admin/subscriptions/delete" method="post">
	<table class="table table-striped">
		<thead>
			<tr>
				<th style="width: 5%">ID</th>
				<th style="width: 85%">email</th>
				<th style="width: 5%"></th>
				<th style="width: 5%">
					<INPUT type="checkbox" onchange="checkAll(this)" name="check.s" value="S_[]"/>
				</th>
			</tr>
		</thead>
		<tbody>
			{LOOP}
			<tr>
				<td align="center">{ID}</td>
				<td>{EMAIL}</td>
				<td><a href="{BASE}admin/subscriptions/delete/{ID}" class="btn btn-sm btn-danger" role="button"><i class="glyphicon glyphicon-trash"></i></a></td>
				<td>
					<input type="checkbox" value="{ID}" name="S_[]">
				</td>
			</tr>
			{/LOOP}
			{ALT}
			<tr>
				<td align="center" colspan="6">
					No results to show.
				</td>
			</tr>
			{/ALT}
		</tbody>
	</table>
	<button name="submit" value="submit" type="submit" class="btn btn-sm btn-danger">Delete</button>
</form>