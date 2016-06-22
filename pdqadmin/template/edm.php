<!DOCTYPE html>
<html lang="en">
<head>
<title>EDM</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<base href="<?php echo $template ?>">
<link rel="stylesheet" href="css/bootstrap.min.css" />
<link rel="stylesheet" href="css/font-awesome.css" />
<link rel="stylesheet" href="css/summernote.css" />
<link rel="stylesheet" href="css/icheck/flat/blue.css" />
<link rel="stylesheet" href="css/select2.css" />
<link rel="stylesheet" href="css/jquery-ui.css" />
<link rel="stylesheet" href="css/jquery.tagsinput.css" />
<link rel="stylesheet" href="css/unicorn.css" />
<link rel="stylesheet" href="css/paddy.css" />
<link rel="stylesheet" href="css/fileinput.css" />
<!--[if lt IE 9]>
        <script type="text/javascript" src="js/respond.min.js"></script>
        <![endif]-->

</head>
<body data-color="grey" class="flat">
	<div class="modal fade" id="contact-con">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">EDM</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12" id="contact-main-con"></div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="modal-footer">
					<span id="customer_email" style="float: left"></span>
					<button type="button" class="btn btn-default" id="contact-con-close" data-dismiss="modal">Close</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
	<div id="wrapper">
            <?php echo $head?>

            <div id="content">
			<div id="content-header" class="mini">
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-lg-6">
						<h1>
							<i class="fa fa-users" aria-hidden="true"></i>EDM
						</h1>
					</div>
				</div>
			</div>
			<div id="breadcrumb">
				<a href="#" title="Go to ContactUs List" class="tip-bottom"><i class="fa fa-tags"></i> EDM </a>
				<a href="#" class="current">EDM List</a>
			</div>


			<div class="row">
				<div class="col-xs-12">
				   <div class="row">
				     <div class="widget-box widget-box-hledit widget-box-hledit-order-left">
						<div class="row">
							<div class="col-lg-12">
								<h1>DrGrab EDM Analytics</h1>
								<p class="lead">Select the Day Range to Start.</p>
								<form action="/edm" method="post">
									<div class="input-daterange input-group">
										<input type="text" class="input form-control dr-date-start datepicker" name="startTime"
											value="<?=$start?>" /> <span class="input-group-addon">to</span> <input type="text"
											class="input form-control dr-date-end datepicker" name="endTime" value="<?=$end?>" /> 
											<span class="input-group-addon">国家</span>
											<select name="country" class="form-control">
												<option value="US" <?php echo $country=='US' ? 'selected=selected' : '';?>>US</option>
												<option value="IE" <?php echo $country=='IE' ? 'selected=selected' : '';?>>IE</option>
												<option value="AU" <?php echo $country=='AU' ? 'selected=selected' : '';?>>AU</option>
												<option value="NZ" <?php echo $country=='NZ' ? 'selected=selected' : '';?>>NZ</option>
												<option value="CA" <?php echo $country=='CA' ? 'selected=selected' : '';?>>CA</option>
												<option value="SG" <?php echo $country=='SG' ? 'selected=selected' : '';?>>SG</option>
												<option value="GB" <?php echo $country=='GB' ? 'selected=selected' : '';?>>GB</option>
											</select>
											<span class="input-group-addon">Campaign</span> <input type="text" class="input form-control"
											name="campaign" value="<?php if(!empty($campaign)){echo $campaign;}?>" /> <span class="input-group-btn">
											<button class="btn btn-default" type="button" onclick="this.form.submit()">Go!</button>
										</span>
									</div>
								</form>
							</div>
						</div>


						<?php if ($totalOrder):?>
						<div class="row">
							<div class="col-xs-12">
								<p>Total revenue : <?=$totalRevenue/100?></p>
								<p>Total Order: <?=$totalOrder?></p>
								<div style="margin-top: 20px;">
									<table class="table table-bordered with-check">
										<thead>
											<tr>
												<th>ID</th>
												<th>Total</th>
												<th>Items</th>
												<th width="50%">Landing Page</th>
											</tr>
										</thead>
										<tbody>
					  					<?php foreach ($orders as $order): ?>
					  						<tr>
					  							<td><?=$order['order_number']?></td>
					  							<td><?=$order['payment_amount']/100?></td>
					  							<td>
					  							<?php foreach ($order['products'] as $product): ?>
					  								<span><?=$product['product_name']?> × <?=$product['product_quantity']?> </span> <br/>
					  							<?php endforeach; ?>
					  							</td>
					  							<td><?=$order['landing_page']?></td>
					  						</tr>
										<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div> 
						<?php endif; ?>
			            </div>
			        </div>
			    </div>
			</div>
			


		</div>
	</div>

	<script src="js/jquery.min.js"></script>
	<script src="js/jquery-ui.custom.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.icheck.min.js"></script>
	<script src="js/select2.min.js"></script>

	<script src="js/jquery.nicescroll.min.js"></script>
	<script src="js/unicorn.js"></script>
	<script src="js/unicorn.jui.js"></script>
	<script src="js/highlight.js"></script>
	<script src="js/bootstrap-switch.js"></script>
	<script src="js/main.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script src="js/jquery.notifyBar.js"></script>

	<script>
		$('.datepicker').datepicker({
		    format: "yyyy-mm-dd",
		    todayHighlight: true
		});		
	</script>
	
           
	<?php echo $foot?>
    </body>
</html>
