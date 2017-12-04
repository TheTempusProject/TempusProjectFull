<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad" >
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Error</h3>
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
										<td>Time submitted:</td>
										<td align="right">{DTC}{time}{/DTC}</td>
									</tr>
									<tr>
										<td>IP:</td>
										<td align="right">{ip}</td>
									</tr>
									<tr>
										<td align="left">Class:</td>
										<td align="right">{class}</td>
									</tr>
									<tr>
										<td align="left">Function</td>
										<td align="right">{function}</td>
									</tr>
									<tr>
										<td align="left">Error:</td>
										<td align="right">{error}</td>
									</tr>
									<tr>
										<td colspan="2">{description}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="panel-footer">
					{ADMIN}
					<form action="{base}admin/errors/delete" method="post">
						<INPUT type="hidden" name="E_" value="{ID}"/>
						<input type="hidden" name="token" value="{TOKEN}" />
						<button name="submit" value="submit" type="submit" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></button>
					</form>
					{/ADMIN}
				</div>
			</div>
		</div>
	</div>
</div>