<!DOCTYPE html>
<html lang="en">
	<head>
		<title>orders</title>
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
							<h1><i class="glyphicon glyphicon-inbox" aria-hidden="true"></i>Orders</h1>
						</div>	
				    </div>	
				</div>
				<div id="breadcrumb">
					<a href="/orders"  class="tip-bottom"><i class="fa fa-tags"></i>Orders</a>
				</div>
				<div class="row">
					<div class="col-xs-12">
					   <div class="row">
					     <div class="widget-box widget-box-hledit widget-box-hledit-order-left">
                          <?php echo form_open('orders'); ?>
                          <div class=" order_search row">
                          	 <div class="col-sm-3">
                          	 	<select name="s_status" class="form-control">
                                    <option value="" <?php if(empty($where[1]))echo " selected";?>>请选择...</option>
                                    <option value="order_number" <?php if($where[1] == 'order_number')echo " selected";?>>订单号码</option>
                          	 		<option value="receive_name" <?php if($where[1] == 'receive_name')echo " selected";?>>收货人名称</option>
                          	 		<option value="receive_add1" <?php if($where[1] == 'receive_add1')echo " selected";?>>收货地址</option>
                          	 		<option value="order_risk" <?php if($where[1] == 'order_risk')echo " selected";?>>风险等级</option>
                          	 		<option value="member_email" <?php if($where[1] == 'member_email')echo " selected";?>>email</option>              
                          	 		<option value="transaction_id" <?php if($where[1] == 'transaction_id')echo " selected";?>>transaction_id</option>
                                    <option value="sku" <?php if($where[1] == 'sku')echo " selected";?>>sku</option> 
                          	 	</select>
                          	 </div>
						     <div class="input-group col-sm-6 nopadding">
                                                         <input type="text" id="order-for-search" class="form-control" name="search" value="<?php if($where[0]!='ALL')echo $where[0];?>" placeholder="Search for..." >
							   <span class="input-group-btn">
							       <input type="submit"   id="order-for-submit" class="btn btn-default" value="Go!" />
							   </span>
							  </div>
						</div>
                        </form>
                        
                        
                        <a class="btn btn-default btn-bgcolor-white" href="/edm">EDM</a>

							<div class="widget-content nopadding">

                               <?php if (empty($orders)):?><span> No GET data exists </span>
                               <?php else: ?>
								<table class="table table-striped table-hover with-check orderlist-table">
									<thead>
										<tr>
										    <!-- <span class="caret"> -->
											<th>Order</th>
											<th>Date</th>
											<th>Customer</th>
											<th>Payment Status</th>
											<th>Fulfillment Status</th>
											<th>Total</th>
										</tr>
									</thead>
									<tbody>

                                         <?php foreach ($orders as $order): ?>
                                       	   <?php if ($order['doc_status'] == 2 || $order['doc_status'] == 3): ?> <tr class="orderlist-table-grayfontstyle">
                                       	   <?php else: ?> <tr>
                                           <?php endif; ?>	
											  <td style="width: 20%">
											    <a href="/ordersContent/<?=$order['order_number']?>"><?=$order['order_number']?>
											    <?php if ($order['order_risk']): ?>
												    <?php if ($order['order_risk'] == 3): ?>
												     	<i class="glyphicon glyphicon-info-sign hight-risk" data-toggle="tooltip" data-placement="bottom" title="This order has a high risk of fraud."></i>
												    <?php elseif ($order['order_risk'] == 2): ?>
												     	<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="bottom" title="This order might be fraudulent."></i>
												    <?php endif; ?>
												<?php else: ?>
														<i class="glyphicon glyphicon-info-sign hight-blue" data-toggle="tooltip" data-placement="bottom" title="此订单未进行风险评估 ,请稍后操作."></i>
												<?php endif; ?>
												<?php if ($order['message']): ?>
														<i class="glyphicon glyphicon-info-sign hight-green" data-toggle="tooltip" data-placement="bottom" title="此订单有客户留言信息."></i>
												<?php endif; ?>
											    </a>
											  </td> 
											  <td><?=date('Y-m-d H:i:s', $order['create_time'])?></td>
											  <td><?=$order['member_name']?></td>
											  <td><span class="badge badge--complete">
											        <?=$sysPayStatus[$order['pay_status']]?>
											      </span>
											  </td>
											  <td><span class="badge badge--complete">
											     <?=$sysSendStatus[end(explode(',',$order['send_status']))]?>
											  </span></td>
											  <td>$<?=$order['payment_amount']/100?></td>
										   </tr>
                                        <?php endforeach; ?>

									</tbody>
								</table>
                             <?php endif; ?>
  

							</div>
						</div>	
					 </div>
					 <ul class="pagination alternate">
                        <?php if (isset($pages)) echo $pages ?>
                     </ul>
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

<!--左侧nav-->
<script src="js/jquery.nicescroll.min.js"></script>
<script src="js/unicorn.js"></script>
<script src="js/unicorn.icheckbox.js"></script>

<?php echo $foot ?>

<!--提示信息插件-->
<script type="text/javascript">
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
})

$('#order-for-search').keypress(function(e){
    var keycode = e.charCode;
        if(keycode == 13)
        $('#order-for-submit').click();
});

</script>
</body>
</html>
