<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header" style="margin: 70px 0 20px 0 !important">Fail Case</h1>
    </div>
</div>
<div class="row" id="row-fail-case">
	<div class="col-lg-12">
        <div class="panel panel-default">
        	<!-- 401 -->
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th width="10%">Code</th>
                                <th width="10%">Status</th>
                                <th width="20%">Message</th>
                                <th width="20%">Data</th>
                                <th width="40%">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="odd">
                                <td>204</td>
                                <td>fail</td>
                                <td>Data not found</td>
                                <td>Empty array</td>
                                <td>Your parameters are completed but data is not found.</td>
                            </tr>
                            <tr class="odd">
                                <td>400</td>
                                <td>fail</td>
                                <td>Bad Request</td>
                                <td>["message"]</td>
                                <td>Something's wrong.</td>
                            </tr>
                            <tr class="odd">
                                <td>401</td>
                                <td>fail</td>
                                <td>Permission Denied</td>
                                <td>Empty array</td>
                                <td>Invalid passcode or token.</td>
                            </tr>
                            <tr class="odd">
                                <td>404</td>
                                <td>fail</td>
                                <td>Found Exception</td>
                                <td>Empty array</td>
                                <td>Found Exception.</td>
                            </tr>
                            <tr class="odd">
                                <td>422</td>
                                <td>fail</td>
                                <td>Validation Failed</td>
                                <td>["field", "message"]</td>
                                <td>Required field is missing.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>