<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad" >
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">{name}</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class=" col-md-9 col-lg-9 "> 
                            <table class="table table-user-primary">
                                <tbody>
                                    <tr>
                                        <td>Query Limit</td>
                                        <td>{pageLimit}</td>
                                    </tr>
                                    <tr>
                                        <td>Send Messages:</td>
                                        <td>{sendMessages_text}</td>
                                    </tr>
                                    <tr>
                                        <td>Upload Images</td>
                                        <td>{uploadImages_text}</td>
                                    </tr>
                                    <tr>
                                        <td>Submit Feedback</td>
                                        <td>{feedback_text}</td>
                                    </tr>
                                    <tr>
                                        <td>Submit Bug Reports</td>
                                        <td>{bugReport_text}</td>
                                    </tr>
                                    <tr>
                                        <td>Can use the Admin Panel</td>
                                        <td>{adminAccess_text}</td>
                                    </tr>
                                    <tr>
                                        <td>Can use the Moderator Panel</td>
                                        <td>{modAccess_text}</td>
                                    </tr>
                                    <tr>
                                        <td>Can Access Member Areas</td>
                                        <td>{memberAccess_text}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <a href="{BASE}admin/groups/edit/{ID}" class="btn btn-sm btn-warning" role="button">Edit</a>
                    <a href="{BASE}admin/groups/delete/{ID}" class="btn btn-sm btn-danger" role="button">Delete</a>
                </div>
            </div>
        </div>
    </div>
</div>