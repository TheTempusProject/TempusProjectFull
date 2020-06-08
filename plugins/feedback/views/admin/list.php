<legend>Feedback</legend>
{PAGINATION}
<form action="{BASE}admin/feedback/delete" method="post">
	<table class="table table-striped">
		<thead>
			<tr>
				<th style="width: 5%">ID</th>
				<th style="width: 25%">Time</th>
				<th style="width: 55%">Feedback</th>
				<th style="width: 5%"></th>
				<th style="width: 5%"></th>
				<th style="width: 5%">
					<INPUT type="checkbox" onchange="checkAll(this)" name="check.f" value="F_[]"/>
				</th>
			</tr>
		</thead>
		<tbody>
			{LOOP}
			<tr>
				<td>{ID}</td>
				<td>{DTC}{time}{/DTC}</td>
				<td>{feedback}</td>
				<td><a href="{BASE}admin/feedback/viewFeedback/{ID}" class="btn btn-sm btn-primary" role="button"><i class="glyphicon glyphicon-open"></i></a></td>
                <td><a href="{BASE}admin/feedback/delete/{ID}" class="btn btn-sm btn-danger" role="button"><i class="glyphicon glyphicon-trash"></i></a></td>
				<td>
					<input type="checkbox" value="{ID}" name="F_[]">
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
<br />
<a href="{BASE}admin/feedback/clear">clear all</a>