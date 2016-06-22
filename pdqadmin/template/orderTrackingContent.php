<!DOCTYPE html>
<html lang="en">
	<head>
	    <base href="<?php echo $template ?>">
		<title>orderTrackingContent</title>
		<meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<link rel="stylesheet" href="css/font-awesome.css" />	
		<link rel="stylesheet" href="css/summernote.css" />
		<link rel="stylesheet" href="css/icheck/flat/blue.css" />
		<link rel="stylesheet" href="css/select2.css" />
		<link rel="stylesheet" href="css/jquery-ui.css" />	
		<link rel="stylesheet" href="css/jquery.tagsinput.css" />	
		<link rel="stylesheet" href="css/unicorn.css" />		
		<link rel="stylesheet" href="css/paddy.css" />	
		<link rel="stylesheet" href="css/fileinput.css"/>	
		<!--[if lt IE 9]>
		<script type="text/javascript" src="js/respond.min.js"></script>
		<![endif]-->
			
	</head>	
	<body data-color="grey" class="flat">
		<div id="wrapper">

			<?php echo $head ?>

			<div id="content">
				<div id="content-header" class="mini">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-lg-6">
							<h1><i class="glyphicon glyphicon-check" aria-hidden="true"></i>orderTrackingContent</h1>
						</div>	
						<!--div class="col-xs-12 col-sm-6 col-lg-6">
							<div class="text-right">
								<button type="button" class="btn btn-default btn-bgcolor-white"><i class="fa fa-pencil"></i> Change Status</button>
							</div>	
						</div-->	
				  </div>
					
				</div>
				<div id="breadcrumb">
					<a href="/admin/products" title="Go to Orders List" class="tip-bottom"><i class="fa fa-tags"></i> orderTracking </a>
					<a href="#" class="current">#<?=$complaintsDetails['complaints_id']?></a>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-8 col-lg-8">
						<div class="widget-box widget-box-hledit widget-box-hledit-productadd-left">
							<div class="widget-title">
								<h4>Product</h4>
							</div>
							<div class="widget-content nopadding">
								<table class="table table-striped ordercontent-table">
									<tbody>
									 	<?php foreach ($order_detail as $details): ?>
										<tr>
											<td class="image" width="50px">
											<a href="#"><img class="ordercontent-image" src="<?= IMAGE_DOMAIN ?>/product/<?=$details['product_sku']?>/<?=$details['product_sku']?>.jpg" /></a>
											</td>
											<td width="45%"><?=$details['product_name']?></a>
											  <h6 class="subdued"><?=$details['product_attr']?></h6>
											  <h6 class="subdued">SKU : <?=$details['product_sku']?></h6>
											</td>
											<td width="12%">$<?=$details['payment_price']/100?>×<?=$details['product_quantity']?></td>
											<td>$<?=$details['payment_amount']/100?></td>
										</tr>
									    <?php endforeach; ?>
									</tbody>
								</table>
							</div>

						</div>

						<?php echo form_open('orderTrackingContent/updateComplaints'); ?>
						<input type="hidden"  name="complaints_id" value="<?=$complaintsDetails['complaints_id']?>" />
						<div class="widget-box widget-box-hledit widget-box-hledit-productadd-left order-tracking-content">
							<div class="widget-title">
								<h4>Information</h4>
							</div>
							<div class="widget-content">
								<div class="row">
									<div class="col-sm-5">
										<h6>问题分类：</h6>
										<select name="question_type" class="form-control" id="">
											<option value="0"  <?php echo $complaintsDetails['question_type']==0 ? 'selected=selected' : '';?> >错发</option>
											<option value="1"  <?php echo $complaintsDetails['question_type']==1 ? 'selected=selected' : '';?> >漏发</option>
											<option value="2"  <?php echo $complaintsDetails['question_type']==2 ? 'selected=selected' : '';?> >丢包</option>
											<option value="3"  <?php echo $complaintsDetails['question_type']==3 ? 'selected=selected' : '';?> >尺码问题</option>
											<option value="4"  <?php echo $complaintsDetails['question_type']==4 ? 'selected=selected' : '';?> >质量问题</option>
											<option value="5"  <?php echo $complaintsDetails['question_type']==5 ? 'selected=selected' : '';?> >物流超时</option>
											<option value="6"  <?php echo $complaintsDetails['question_type']==6 ? 'selected=selected' : '';?> >取消订单</option>
											<option value="7"  <?php echo $complaintsDetails['question_type']==7 ? 'selected=selected' : '';?> >退回中国</option>
											<option value="8"  <?php echo $complaintsDetails['question_type']==8 ? 'selected=selected' : '';?> >尚未发货</option>
											<option value="9"  <?php echo $complaintsDetails['question_type']==9 ? 'selected=selected' : '';?> >其他</option>
										</select>
									</div>
									<div class="col-sm-5 col-sm-offset-1">
										<h6>责任部门：</h6>
	                                    <select name="department" class="form-control">
	                                        <option value="0" <?php echo $complaintsDetails['department']==0 ? 'selected=selected' : '';?> >销售部</option>
	                                        <option value="1" <?php echo $complaintsDetails['department']==1 ? 'selected=selected' : '';?> >运营部</option>
	                                        <option value="2" <?php echo $complaintsDetails['department']==2 ? 'selected=selected' : '';?> >客服部</option>
	                                        <option value="3" <?php echo $complaintsDetails['department']==3 ? 'selected=selected' : '';?> >ERP</option>
	                                        <option value="4" <?php echo $complaintsDetails['department']==4 ? 'selected=selected' : '';?> >邮路</option>
	                                    </select>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="row col-sm-12 nopadding">
									<div class="col-sm-12">
										<h6 for="bundlePrice">问题备注：</h6>
										<textarea class="form-control" rows="3"  name="question_remark"><?=$complaintsDetails['question_remark']?></textarea>
									</div>
									<div class="col-sm-12">
										<h6 for="bundlePrice">退款备注：</h6>
										<textarea class="form-control" rows="2"  name="refund_remark"><?=$complaintsDetails['refund_remark']?></textarea>
									</div>
									<div class="clearfix"></div>
								</div>

								<div class="clearfix"></div>	
							</div>
							<div class="row col-sm-12">						
						    	<h6>处理方式</h6>
								<div class="form-group col-sm-5 nopadding">
									<select name="dispose" class="form-control" id="treatment">
										<option value="0" <?php echo $complaintsDetails['dispose']==0 ? 'selected=selected' : '';?> >发货</option>
										<option value="1" <?php echo $complaintsDetails['dispose']==1 ? 'selected=selected' : '';?> >重寄</option>
										<option value="2" <?php echo $complaintsDetails['dispose']==2 ? 'selected=selected' : '';?> >退款</option>
										<option value="3" <?php echo $complaintsDetails['dispose']==3 ? 'selected=selected' : '';?> >退运费</option>
										<option value="4" <?php echo $complaintsDetails['dispose']==4 ? 'selected=selected' : '';?> >退关税</option>
										<option value="5" <?php echo $complaintsDetails['dispose']==5 ? 'selected=selected' : '';?> >Coupon</option>
									</select>
								</div>
								<div class="form-group col-sm-4">
									<input name="refund_amount" type="text" class="form-control" id="price" placeholder="$" value="<?=$complaintsDetails['refund_amount']?>" style="display:none">
									<select name="coupon" class="form-control" id="coupon" style="display:none">
										<option value="1" <?php echo $complaintsDetails['coupon']==1 ? 'selected=selected' : '';?> >Coupon10%</option>
										<option value="2" <?php echo $complaintsDetails['coupon']==2 ? 'selected=selected' : '';?> >Coupon15%</option>
										<option value="3" <?php echo $complaintsDetails['coupon']==3 ? 'selected=selected' : '';?> >Coupon20%</option>
										<option value="4" <?php echo $complaintsDetails['coupon']==4 ? 'selected=selected' : '';?> >Coupon30%</option>
									</select>
								</div>
								<div class="clearfix"></div>	
							</div>
							<div class="clearfix"></div>	




							
							<div class="produccontent-buttomsave-box">
								<div class="pull-right">
									<button type="submit" class="btn btn-default btn-bgcolor-blue">Save change</button>
								</div>
								<div class="clearfix"></div>
						    </div>
									

							<div class="clearfix"></div>

						</div>	
                       </from>
						
					</div>
					<div class="col-xs-12 col-sm-4 col-lg-4">
						<div class="widget-box widget-box-hledit widget-box-hledit-productcontent-right">
							<div class="widget-title">
								<h4>Order Number</h4>
							</div>
							<div class="widget-content nopadding">
								<input type="text" class="form-control" readonly value="<?=$complaintsDetails['order_number']?>">
							</div>
							<div class="clearfix"></div>
						</div>	
						<div class="widget-box widget-box-hledit widget-box-hledit-productcontent-right">
						    <div class="widget-title">
								<h4>Order information</h4>
							</div>
							<div class="widget-content nopadding">
								  <div class="form-group">
									<label for="">Member</label>
									<input type="text" class="form-control" readonly value="<?=$complaintsDetails['member_name']?>">
								  </div>
								  <div class="form-group">
									<label for="">Date</label>
									<input type="text" class="form-control" readonly value="<?=date('Y-m-d H:i:s', $complaintsDetails['create_time'])?>">
								  </div>
								  <div class="form-group">
									<label for="">发货单</label>
									<input type="text" class="form-control" readonly value="<?=$complaintsDetails['send_bill']?>">
								  </div>
								  <div class="form-group">
									<label for="">发货日期</label>
									<input type="text" class="form-control" readonly value="<?=date('Y-m-d H:i:s', $complaintsDetails['send_time'])?>">
								  </div>
								  <div class="form-group">
									<label for="">问题物流方式</label>
									<input type="text" class="form-control" readonly value="<?=$complaintsDetails['logistics']?>">
								  </div>
								  <div class="form-group">
									<label for="">跟踪号/单号</label>
									<input type="text" class="form-control" readonly value="<?=$complaintsDetails['track_code']?>">
								  </div>
							</div>
						</div>	

	
					</div>
					<div class="clearfix"></div>
					
				</div>
				</form>
			</div>
			<div class="row">
				<div id="footer" class="col-xs-12"></div>
			</div>
		</div>

        <script src="js/jquery.min.js"></script>
        <script src="js/jquery-ui.custom.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.icheck.min.js"></script>
        <script src="js/select2.min.js"></script>
        <script src="js/sortable.min.js"></script>
        <script src="js/fileinput.min.js"></script>
        
        <!--左侧nav-->
        <script src="js/jquery.nicescroll.min.js"></script>
        <script src="js/unicorn.js"></script>
        
        <script src="js/jquery.tagsinput.js"></script>
        <script src="js/bootstrap3-typeahead.min.js"></script>
        
        <!--文本编辑器-->
        <script src="js/summernote.js"></script>
        <?php echo $foot ?>
		<script>
			$(function(){
				
				//处理方式
				$val = $('#treatment option:selected').val();
				if($val == '3' || $val == '2' || $val == '4'){
					$("#price").fadeIn(100);
				}else if($val == '5'){
					$('#coupon').fadeIn(100);
				};
				$('#treatment').on('change',function(){
					$val = $(this).val();
					if($val == '3' || $val == '2' || $val == '4'){
						$("#price").fadeIn(100).siblings().fadeOut(1);
					}else if($val == '5'){
						$('#coupon').fadeIn(100).siblings().fadeOut(1);
					}else{
						$("#price").fadeOut(1);
						$('#coupon').fadeOut(1);
					};
				});
				$('.fa-pencil').on('click',function(){
					$('#treatment').attr('disabled',false);
				})

			});
		</script>
	</body>
</html>
