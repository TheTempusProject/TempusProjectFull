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
									<tr>
										<td>Group:</td>
										<td><a href="{BASE}/admin/groups/viewGroup/{userGroup}">{groupName}</a></td>
									</tr>
									{/ADMIN}
									<tr>
										<td>Registered:</td>
										<td>{DTC date}{registered}{/DTC}</td>
									</tr>
									<tr>
										<td>Last seen</td>
										<td>{DTC date}{lastLogin}{/DTC}</td>
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
					<a href="{base}usercp/messages/newMessage/{USERNAME}" data-original-title="Broadcast Message" data-toggle="tooltip" type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-envelope"></i></a>
					{ADMIN}
					<span class="pull-right">
						<form action="{base}admin/users" method="post">
							<input type="hidden" name="U_" value="{ID}"/>
							<input type="hidden" name="token" value="{TOKEN}" />
							<button name="submit" value="delete" type="submit" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></button>
							<button name="submit" value="edit" type="submit" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-edit"></i></button>
						</form>
					</span>
					{/ADMIN}
				</div>
			</div>
		</div>
	</div>
</div>