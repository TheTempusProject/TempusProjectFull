<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad" >
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Model Info</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class=" col-md-12 col-lg-12 "> 
							<table class="table table-user-primary">
								<tbody>
									<tr>
										<td align="left" width="200">Name:</td>
										<td align="right">{name}</td>
									</tr>
									<tr>
										<td>Status:</td>
										<td align="right">{installStatus}</td>
									</tr>
									<tr>
										<td>Installed:</td>
										<td align="right">{DTC}{installDate}{/DTC}</td>
									</tr>
									<tr>
										<td>Last Updated:</td>
										<td align="right">{DTC}{lastUpdate}{/DTC}</td>
									</tr>
									<tr>
										<td>File Version:</td>
										<td align="right">{version}</td>
									</tr>
									<tr>
										<td>Installed Version:</td>
										<td align="right">{currentVersion}</td>
									</tr>
									<tr>
										<td>installDB:</td>
										<td align="right">{installDB}</td>
									</tr>
									<tr>
										<td>installPermissions:</td>
										<td align="right">{installPermissions}</td>
									</tr>
									<tr>
										<td>installConfigs:</td>
										<td align="right">{installConfigs}</td>
									</tr>
									<tr>
										<td>installResources:</td>
										<td align="right">{installResources}</td>
									</tr>
									<tr>
										<td>installPreferences:</td>
										<td align="right">{installPreferences}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="panel-footer">
                    <a href="{BASE}admin/installed/install/{name}" class="btn btn-sm btn-warning" role="button">Install</a>
                    <a href="{BASE}admin/installed/uninstall/{name}" class="btn btn-sm btn-danger" role="button">Uninstall</a>
                </div>
			</div>
		</div>
	</div>
</div>