<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad" >
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Admin Log</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class=" col-md-12 col-lg-12 "> 
							<table class="table table-user-primary">
								<tbody>
									<tr>
										<td align="left" width="200">ID</td>
										<td align="right">{ID}</td>
									</tr>
									<tr>
										<td>User:</td>
										<td align="right">{logUser}</td>
									</tr>
									<tr>
										<td>Time:</td>
										<td align="right">{DTC}{time}{/DTC}</td>
									</tr>
									<tr>
										<td>IP:</td>
										<td align="right">{ip}</td>
									</tr>
									<tr>
										<td align="left">Log:</td>
										<td align="right">{action}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="panel-footer">
					{ADMIN}
					<form action="{base}admin/logins/delete" method="post">
						<INPUT type="hidden" name="L_" value="{ID}"/>
						<input type="hidden" name="token" value="{TOKEN}" />
						<button name="submit" value="submit" type="submit" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></button>
					</form>
					{/ADMIN}
				</div>
			</div>
		</div>
	</div>
</div>