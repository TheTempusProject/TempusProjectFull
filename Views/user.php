<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad" >
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">{username}</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-3 col-lg-3 " align="center">
							<img alt="User Pic" src="{BASE}{avatar}" class="img-circle img-responsive">
						</div>
						<div class=" col-md-9 col-lg-9 "> 
							<table class="table table-user-primary">
								<tbody>
									{ADMIN}
									<tr>
										<td>Confirmed:</td>
										<td>{confirmedText}</td>
									</tr>
									{/ADMIN}
									<tr>
										<td>registered:</td>
										<td>{DTC}{registered}{/DTC}</td>
									</tr>
									<tr>
										<td>Last seen</td>
										<td>{DTC}{lastLogin}{/DTC}</td>
									</tr>
									<tr>
										<td>Gender</td>
										<td>{gender}</td>
									</tr>
									{ADMIN}
									<tr>
										<td>Email</td>
										<td><a href="mailto:{email}">{email}</a></td>
									</tr>
										<td>User ID</td>
										<td>{ID}</td>
									</tr>
									{/ADMIN}
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<a href="{base}usercp/messages/newmessage?prepopuser={USERNAME}" data-original-title="Broadcast Message" data-toggle="tooltip" type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-envelope"></i></a>
					{ADMIN}
					<span class="pull-right">
						<a href="{base}admin/users/edit/{ID}" data-original-title="Edit this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
						<a href="{base}admin/users/delete/{ID}" data-original-title="Remove this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></a>
					</span>
					{/ADMIN}
				</div>
			</div>
		</div>
	</div>
</div>