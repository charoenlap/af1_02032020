<!DOCTYPE html>
<html>
<head>
	<title><?php echo env('APP_NAME').' API DOCUMENT' ?></title>

	<link href="<?php echo urlBase().'/assets/api/bower_components/bootstrap/dist/css/bootstrap.min.css' ?>" rel="stylesheet">
	<link href="<?php echo urlBase().'/assets/api/bower_components/metisMenu/dist/metisMenu.css' ?>" rel="stylesheet">
	<link href="<?php echo urlBase().'/assets/api/bower_components/font-awesome//css/font-awesome.min.css' ?>" rel="stylesheet">
	<link href="<?php echo urlBase().'/assets/api/css/sb-admin.css' ?>" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo urlBase().'/assets/api/bower_components/roboto/roboto.css' ?>" >
</head>
<body style="font-family: Roboto">
	<div id="wrapper" >

		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" style="" >
        	<?php echo view('api.layouts.header') ?>
        	<?php echo view('api.layouts.side_bar', compact('models')) ?>
        	<?php echo view('api.layouts.top_bar') ?>
        </nav>

        <!-- Header -->
        <div id="page-wrapper">
        	<div class="container-fluid">
        	<?php foreach ($models as $model): ?>
	        	<!-- Class -->
	        	<?php if (isset($model['class'])): ?>
	        	<div class="row">
				    <div class="col-lg-12">
				        <h1 class="page-header" style="margin: 70px 0 20px 0 !important"><?php echo $model['class'] ?></h1>
				    </div>
				</div>
				<?php endif ?>
				<!-- Body -->
				<?php if (isset($model['class'])): ?>
				<?php foreach ($model['method'] as $method): ?>
					<div class="row" id="<?php echo 'row-'.strtolower($model['class']).'-'.strtolower($method['verb']) ?>">
						<div class="col-lg-12">
					        <div class="panel panel-default">
					        	<!-- Method Detail-->
					        	<div class="panel-heading" style="font-size: 16px; min-height: 100px">
					        		<div>
					        			<div class="col-sm-2" style="color: #5bc0de">Method</div>
					        			<div class="col-sm-10"><?php echo $method['verb'] ?></div>
					        		</div>
					        		<div>
					        			<div class="col-sm-2" style="color: #5bc0de">Path</div>
					        			<div class="col-sm-10"><?php echo $method['path'] ?></div>
					        		</div>
					        		<div>
					        			<div class="col-sm-2" style="color: #5bc0de">Description</div>
					        			<div class="col-sm-10"><?php echo $method['description'] ?></div>
					        		</div>
					        	</div>
					        	<!-- Parameter -->
					            <div class="panel-body">
									<h4>Parameter</h4>
					                <div class="dataTable_wrapper">
					                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
					                        <thead>
					                            <tr>
					                                <th width="30%">Field</th>
					                                <th width="20%">Type</th>
					                                <th width="35%">Description</th>
					                                <th width="15%">Requirement</th>
					                            </tr>
					                        </thead>
					                        <tbody>
					                        	<?php if (isset($method['parameter'])): ?>
					                        	<?php foreach ($method['parameter'] as $key => $detail): ?>
						                            <tr class="odd">
						                                <td><?php echo $key ?></td>
						                                <td><?php echo isset($detail['type']) ? $detail['type'] : '' ?></td>
						                                <td><?php echo isset($detail['desc']) ? $detail['desc'] : '' ?></td>
						                                <td><?php echo isset($detail['req']) ? $detail['req'] : '' ?></td>
						                            </tr>
						                        <?php endforeach ?>
						                    	<?php endif ?>
					                        </tbody>
					                    </table>
					                </div>
					            </div>
					            <!-- Success -->
					            <div class="panel-body" style="margin-top: -40px">
					            	<h4>Success</h4>
					                <div class="dataTable_wrapper">
					                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
					                        <thead>
					                            <tr>
					                                <th width="30%">Field</th>
					                                <th width="20%">Type</th>
					                                <th width="50%">Description</th>
					                            </tr>
					                        </thead>
					                        <tbody>
					                        	<?php if (isset($method['success'])): ?>
					                        	<!-- Indent 1 -->
					                            <?php foreach ($method['success'] as $key => $detail): ?>
						                            <tr class="odd">
						                                <td><?php echo $key ?></td>
						                                <td><?php echo isset($detail['type']) ? $detail['type'] : '' ?></td>
						                                <td><?php echo isset($detail['desc']) ? $detail['desc'] : '' ?></td>
						                            </tr>
						                            <!-- Indent 2 -->
						                            <?php if (isset($detail['data'])): ?>
						                            	<?php foreach ($detail['data'] as $key_s2 => $detail_s2): ?>
							                            	<tr class="odd">
								                                <td style="padding-left: 20px"><?php echo $key_s2 ?></td>
								                                <td><?php echo isset($detail_s2['type']) ? $detail_s2['type'] : '' ?></td>
								                                <td><?php echo isset($detail_s2['desc']) ? $detail_s2['desc'] : '' ?></td>
							                            	</tr>
							                            	<!-- Indent 3 -->
							                            	<?php if (isset($detail_s2['data'])): ?>
								                            	<?php foreach ($detail_s2['data'] as $key_s3 => $detail_s3): ?>
									                            	<tr class="odd">
										                                <td style="padding-left: 40px"><?php echo $key_s3 ?></td>
										                                <td><?php echo isset($detail_s3['type']) ? $detail_s3['type'] : '' ?></td>
										                                <td><?php echo isset($detail_s3['desc']) ? $detail_s3['desc'] : '' ?></td>
									                            	</tr>
									                        	<!-- Indent 4 -->
								                            	<?php if (isset($detail_s3['data'])): ?>
									                            	<?php foreach ($detail_s3['data'] as $key_s4 => $detail_s4): ?>
										                            	<tr class="odd">
											                                <td style="padding-left: 40px"><?php echo $key_s4 ?></td>
											                                <td><?php echo isset($detail_s4['type']) ? $detail_s4['type'] : '' ?></td>
											                                <td><?php echo isset($detail_s4['desc']) ? $detail_s4['desc'] : '' ?></td>
										                            	</tr>
									                            	<?php endforeach ?>
									                        	<?php endif ?>
									                        	<!-- /Indent 4 -->
								                            	<?php endforeach ?>
								                        	<?php endif ?>
								                        	<!-- /Indent 3 -->
						                            	<?php endforeach ?>
						                        	<?php endif ?>
						                        	<!-- /Indent 2 -->
						                        <?php endforeach ?>
						                        <!-- /Indent 1 -->
						                        <?php endif ?>
					                        </tbody>
					                    </table>
					                </div>
					            </div>
					        </div>
					    </div>
					</div>
				<?php endforeach ?>
				<?php endif ?>
			<?php endforeach ?>

			<!-- FAIL CASE -->
			<?php echo view('api.layouts.fail_case') ?>

			</div>
        </div>
    </div>

    <!-- Script -->
	<script src="<?php echo urlBase().'/assets/api/bower_components/jquery/dist/jquery.min.js'?>" type="text/javascript"></script>
	<script src="<?php echo urlBase().'/assets/api/bower_components/bootstrap/dist/js/bootstrap.min.js'?>" type="text/javascript"></script>
	<script src="<?php echo urlBase().'/assets/api/bower_components/metisMenu/dist/metisMenu.js'?>" type="text/javascript"></script>
	<script src="<?php echo urlBase().'/assets/api/js/custom.js'?>" type="text/javascript"></script>
	<script src="<?php echo urlBase().'/assets/api/js/sb-admin-2.js'?>" type="text/javascript"></script>
	<script src="<?php echo urlBase().'/assets/api/js/jquery.smooth-scroll.js'?>" type="text/javascript"></script>
</body>
</html>


