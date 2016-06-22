<!DOCTYPE html>
<html lang="en">
<head>
<title>Collection Rank</title>
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
<style>
    #s2id_autogen3{
        border-radius:0 !important;
        display: table-cell;
        margin: 5px;
    }
</style>
</head>
<body data-color="grey" class="flat">
	<div class="modal fade" id="contact-con">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title">Collection Rank</h4>
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
							<i class="fa fa-users" aria-hidden="true"></i>Collection Rank
						</h1>
					</div>
				</div>
			</div>
			<div id="breadcrumb">
				<a href="#" title="Go to ContactUs List" class="tip-bottom"><i class="fa fa-tags"></i> Collection Rank </a>
				<a href="#" class="current">rank</a>
			</div>


			<div class="row">
				<div class="col-xs-12">
				   <div class="row">
				     <div class="widget-box widget-box-hledit widget-box-hledit-order-left">
						<div class="row">
							<div class="col-lg-12">
								<form action="/collection/rank" method="post">
									<div class="input-daterange input-group">
										<input type="text" class="input form-control dr-date-start datepicker" name="startTime" value="<?=$start?>" /> 
										<span class="input-group-addon">to</span> 
										<input type="text" class="input form-control dr-date-end datepicker" name="endTime" value="<?=$end?>" /> 
										<span class="input-group-addon">name</span>
										<select class="selectbox selectzw" name="collectionName">
                                                                                    <?php
                                                                                    foreach ($collection as $vo) {
                                                                                        if ($vo['title']==$collectionName) {
                                                                                            echo '<option value="' . $vo['title'] . '" selected="selected">' . $vo['title'] . '</option>';
                                                                                        } else {
                                                                                            echo '<option value="' . $vo['title'] . '">' . $vo['title'] . '</option>';
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                </select> 
                                                                                <span class="input-group-btn">
											<button class="btn btn-default" type="button" onclick="this.form.submit()">Go!</button>
										</span>
									</div>
								</form>
							</div>
						</div>
						

						<?php if ($collectionRank):?>
						<div class="row">
							<div class="col-xs-12">
								<table class="table table-bordered with-check">
										<thead>
											<tr>
												<th>country</th>
												<th>sales</th>
												<th>total</th>
											</tr>
										</thead>
										<tbody>
					  					<?php foreach ($collectionRank as $key=>$rank): ?>
					  						<tr>
					  							<td><?=$key?></td>
					  							<td><?=$rank['qty']?></td>
					  							<td><?=$rank['amount']?></td>
					  						</tr>
										<?php endforeach; ?>
										
										<tr>
					  						<td>总数</td>
					  						<td><?=$allQty?></td>
					  						<td><?=$allAmount?></td>
					  					</tr>
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
                $(function(){
                    $('.selectbox').select2();
                })
	</script>
	
           
	<?php echo $foot?>
    </body>
</html>
