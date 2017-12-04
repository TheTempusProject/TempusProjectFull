<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad" >
			<div class="panel panel-primary">
				{LOOP}
				{SINGLE}
				<div class="panel-heading">
					<h3 class="panel-title">{subject}</h3>
				</div>
				{/SINGLE}
				<div class="panel-body">
					<div class="row">
						<div class="col-md-3 col-lg-3 " align="center">
							<a href="{BASE}home/profile/{userFrom}">{userFrom}</a><br>
							<img alt="User Pic" src="{BASE}{fromAvatar}" class="img-circle img-responsive">
						</div>
						<div class=" col-md-9 col-lg-9 "> 
							<table class="table table-user-information">
								<tbody>
									<td>{message}</td>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="panel-footer">
					{ADMIN}
					{ID}
					<span class="pull-right">
						{DTC}{sent}{/DTC}
					</span>
					{/ADMIN}
				</div>
				{/LOOP}
			</div>
			<form action="{BASE}usercp/messages/reply" method="post">
				<input type="hidden" name="token" value="{TOKEN}">
				<input type="hidden" name="messageID" value="{PID}">
				<button name="submit" value="reply" type="submit" class="btn btn-sm btn-primary">Reply</button>
			</form>
		</div>
	</div>
</div>