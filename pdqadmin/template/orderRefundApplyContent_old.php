<!DOCTYPE html>
<html lang="en">
	<head>
		<title></title>
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
		<div class="modal produccontent-button-Refunddialog" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true" id="order-refund-con">
        	<div class="modal-dialog">


        	  <div class="modal-content">
        		<div class="modal-header">
        		  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		  <h4 class="modal-title" id="gridSystemModalLabel">Refund Payments</h4>
        		</div>
        
               
                <?php echo form_open('ordersContent/add_refund'); ?>
                <input type="hidden"  name="order_number" value="<?=$orders['order_number']?>" />
                <input type="hidden"  name="r_id" value="<?=$refundApplyDetails['_id']?>" />
                <input type="hidden"  name="refund_status" value="2" />
        		<div class="modal-body">
        		  <div class="container-fluid">
        			<table class="table Refund-table">
        				<tbody>
        					<tr>
        					  <th>Product</th>
        					  <th>Price</th>
        					  <th>Ordered</th>
        					  <th>Returned</th>
        					  <th>Return</th>
        					</tr>
        
        					 <?php foreach ($detail as $key => $details): ?>
        					   <tr class="sku-<?=$key?>">	
        					    <td>
        						   <a href="#"><?=htmlspecialchars_decode($details['product_name'])?></a>
        						   <h6> <?=$details['product_attr']?></h6>
        						   <h6><strong class="note subdued">SKU:</strong> <?=$details['product_sku']?></h6>
        					    </td>
        					    <td><?= $this->session->userdata('my_currency') ?><span class="Payments-modal-price"><?=$details['payment_price']/100?></span></td>
        					    <td class="quantityval"><?=$details['product_quantity']?></td>
                                <!--判断是否为退货start-->
        					    <?php if ($refunds):?>
        					      <td class="refund-qty">
        					        <?php if(isset($details['refund_quantity'])) :?>
                                       <?=$details['refund_quantity']?>
                                    <?php else: ?>
                                       0   
        					        <?php endif; ?>
                                  </td>
                                <?php else: ?>
                                 <td>0</td>
                                <?php endif; ?>
                                <!--判断是否为退货end-->
                                <td>
        					      <div class="product-attribute-add qty_cart">  
        						   <button title="Increase Qty" onClick="qtyUp('sku-<?=$key?>'); return false;" class="increase">+</button>
        					       <input name="re_quantity[]" value="<?php echo isset($details['apply_quantity'])  ? $details['apply_quantity'] : 0 ?>" size="4" title="Qty" class="input-text qty quantity-valinput" maxlength="12" readonly id="quantity-valinput">
        						   <button title="Decrease Qty" onClick="qtyDown('sku-<?=$key?>'); return false;" class="decrease">-</button>	
        					      </div>
        					    </td>	
                                


        					    </td>
        					   </tr>
        					 <?php endforeach; ?>
        


        				</tbody>
        			</table>	
        			<table class="table Restock-table">
        				<tbody>
        
        					<tr>	
        					  <td>Shipping</td>
        					  <td><?= $this->session->userdata('my_currency') ?><?=$orders['freight_amount']/100?></td>
        					</tr>
                           <?php if ($refunds):?>  
                               	 <tr>	
        					  		<td>Refunded:</td>
        					  		<td><?= $this->session->userdata('my_currency') ?><?=$amount/100 ?></td>
        						 </tr>
                           <?php endif; ?>
        					<tr>	
        					  <td>Total available to refund:</td>
        					  <td><?= $this->session->userdata('my_currency') ?><span id="total"><?=$orders['payment_amount']/100-$amount/100?></span></td>
        					</tr>
        				</tbody>
        			</table>
        			<table class="table Restock-paypal">
        				<tbody>
        					<tr>	
        					  <td><i class="fa fa-paypal"></i>Refund with: (For Testing) Bogus Gateway(•••• •••• •••• 1)</td>
        					  <td>
        							<div class="input-group">
        							  <div class="input-group-addon"><?= $this->session->userdata('my_currency') ?></div>
        							  	 <input type="text" class="form-control Refundwith-input" id="Refundwith-input" value="<?=$refund_amount/100?>" placeholder="0.00" name="re_amount">
        							</div>
        					  </td>
        					</tr>
        				</tbody>
        			</table>								
        			<div class="row Reasonrefundbox">
                        <div class="col-xs-6">
                            <label for="exampleInputEmail1">Reason for Return</label>
                            <select class="selectpicker" data-width="90%" name="re_reason">
                                <option value="Faulty"      <?php echo $refundApplyDetails['refund_reason'] == 'Faulty' ? 'selected="selected"' : '' ?>>Faulty</option>
                                <option value="Damaged"     <?php echo $refundApplyDetails['refund_reason'] == 'Damaged' ? 'selected="selected"' : '' ?>>Damaged</option>
                                <option value="Incorrect"   <?php echo $refundApplyDetails['refund_reason'] == 'Incorrect' ? 'selected="selected"' : '' ?>>Incorrect</option>
                                <option value="Change of Mind"  <?php echo $refundApplyDetails['refund_reason'] == 'Change of Mind' ? 'selected="selected"' : '' ?>>Change of Mind</option>
                            </select>
                        </div>
            			<div class="col-xs-6">
                            <label for="exampleInputEmail1">Preferred Resolution</label>
                            <select class="selectpicker" data-width="90%" name="re_resolution">
                                <option value="Store Credit"    <?php echo $refundApplyDetails['refund_resolution'] == 'Store Credit' ? 'selected="selected"' : '' ?>>Store Credit</option>
                                <option value="Cash Refund"  <?php echo $refundApplyDetails['refund_resolution'] == 'Cash Refund' ? 'selected="selected"' : '' ?>>Cash Refund</option>
                            </select>
                        </div> 
                        <div class="col-xs-12">
                            <label for="Reasonrefund"><b>Reason for refund (optional)</b></label>
                            <input type="text" class="form-control" value="<?= $refundApplyDetails['refund_details'] ?>" id="Reasonrefund" name="re_details">
                        </div>		
        			</div>
        		  </div>
        		</div>
        
        		<div class="modal-footer">
        		   <div class="checkbox pull-left text-left">
        				<label class="subdued">
        				  <input type="checkbox" checked="checked">Send a <a href="#">notification email</a> to the customer
        				</label>
        			</div>
        		  <button type="button" class="btn btn-default btn-bgcolor-white" data-dismiss="modal">Cancel</button>
        		  <button type="submit" class="btn btn-default btn-bgcolor-white" id="Refundbtn">Refund</button>
        		</div>
        
        
        	  </div><!-- /.modal-content -->
             </form>

        	</div><!-- /.modal-dialog -->
        </div><!-- /.modal -->  	
		<div id="wrapper">
			<?php echo $head ?>
			<div id="content">
				<div id="content-header" class="mini">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-lg-6">
							<h1><i class="glyphicon glyphicon-check" aria-hidden="true"></i>ordersRefundApply</h1>
						</div>		
				  </div>
					
				</div>
				<div id="breadcrumb">
					<a href="/orderRefundApply" title="Go to orderRefundList" class="tip-bottom"><i class="fa fa-tags"></i> ordersRefundApply </a>
					<a href="javascript:void(0);" class="current"><?= $refundApplyDetails['_id']?> <?=date('Y-m-d H:i:s', $refundApplyDetails['create_time'])?></a>
				</div>
				<div class="row">	
					<div class="col-xs-12 col-sm-12 col-lg-12">
					
						<div class="widget-box widget-box-hledit widget-box-hledit-ordercontent-left widget-box-hledit-ordercontent-tableleft">
							<div class="widget-title">
								<h4 class="pull-left">Orders Refund Apply</h4>
							</div>
							<div class="widget-content nopadding">
								<table class="table table-striped ordercontent-table">
									<tbody>
								<?php foreach ($products as $product): ?>
										<tr>
											<td class="image" width="50px">
											   <a href="/product/edit/<?= $product['product_id'] ?>">
											     <img  class="block" src="<?= $product['image'] ?>" />
											   </a>
											</td>
											<td width="45%">
											    <a href="/product/edit/<?= $product['product_id'] ?>" class="ordercontent-table-tda">
											    	<?= htmlspecialchars_decode($product['product_name'])?>
											    </a>
											  <h6 class="subdued"><?= $product['product_attr']?></h6>
											  <h6 class="subdued">SKU : <?= $product['bundle_skus']?></h6>
											</td>
											<td width="12%"><?= $this->session->userdata('my_currency') . $product['payment_price'] / 100 ?>×<?= $product['product_quantity'] ?></td>
											<td><?= $this->session->userdata('my_currency') . $product['payment_price'] * $product['product_quantity'] /100 ?></td>
										</tr>
								<?php endforeach; ?> 

										
										<tr>
											<td class="image" width="50px"></td>
											<td width="45%"></td>
											<td width="12%"></td>
											<td width="36%">
											  <table class="table--nested table--no-border type--right">
                                                    <tbody>
                                                        <tr class="next-heading">
                                                            <td>refund_quantity</td>
                                                            <td><?= $refund_quantity?></td>
                                                        </tr>

                                                        <tr class="next-heading">
                                                            <td>refund_amount</td>
                                                            <td><?= $this->session->userdata('my_currency').$refund_amount/100 ?></td>
                                                        </tr>
                                                        <tr class="next-heading">
                                                            <td>Reason for Return</td>
                                                            <td><?=$refundApplyDetails['refund_reason']?></td>
                                                        </tr>
                                                        <tr class="next-heading">
                                                            <td>Preferred Resolution</td>
                                                            <td><?= $refundApplyDetails['refund_resolution'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="next-heading">refund_details</td>
                                                            <td class="refund_details" style="width:300px; word-break:break-all"><?= $refundApplyDetails['refund_details'] ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="row orcbtn">
								<div class="col-sm-12 text-right">
								 <?php if ($refundApplyDetails['status']==1):?>
									<button type="button" class="btn btn-info" data-toggle="modal" data-target="#order-refund-con">审核</button>
								 <?php endif; ?>
								</div>
							</div>
						</div>	
						
					</div>
					<div class="clearfix"></div>
				</div>	
			</div>
			<div class="row">
				<div id="footer" class="col-xs-12"></div>
			</div>
		</div>

		<script src="js/jquery.min.js"></script>
		<script src="js/jquery-ui.custom.js"></script>
		<script src="js/bootstrap.min.js"></script>
        <script src="js/select2.min.js"></script>
		<!--左侧nav-->
		<script src="js/jquery.nicescroll.min.js"></script>
		<script src="js/unicorn.js"></script>

		<script>
		$('#myTab a').click(function (e) {
		  e.preventDefault();
		  $(this).tab('show');
		})

        $('.selectpicker').select2();
		//购买产品数量函数
		function qtyDown(){
			var qty_el = document.getElementById('quantity-valinput');
			var qty = qty_el.value;
			if(qty <=0) return false;
			qty = --qty_el.value; 
			var Paymentspricetext = Number($(".Payments-modal-price").text());
			if( !isNaN( qty ) && qty > 0 ){ 
				$("#Refundwith-input").val(Number((Paymentspricetext*(parseFloat($("#quantity-valinput").val())))).toFixed(2));
				var Refundwithval = parseFloat($("#Refundwith-input").val());  
				$(".Refundprice").text(Refundwithval);
				$("#Refundbtn").removeAttr("disabled");
			}else if(qty == 0){
				$("#Refundbtn").removeClass("btn-bgcolor-blue");$("#Refundbtn").addClass("btn-bgcolor-white");
				$(".Refundprice").text("0.00");
				$("#Refundbtn").attr("disabled","disabled");
				$("#Refundwith-input").val("0.00");
			}        
			return false;
		}

		function qtyUp(){
			var qty_el = document.getElementById('quantity-valinput');
			var qty = qty_el.value;  
			var quantityval = Number($(".quantityval").text()); 
			var Paymentspricetext = $(".Payments-modal-price").text();
			if( !isNaN( qty ) && qty < quantityval) { 
				qty_el.value++;

				//$("#Refundwith-input").val((Paymentspricetext*(parseFloat($("#quantity-valinput").val())))).toFixed(2);

				$("#Refundwith-input").val(Number((Paymentspricetext*(parseFloat($("#quantity-valinput").val())))).toFixed(2));

				$("#Refundbtn").addClass("btn-bgcolor-blue"); $("#Refundbtn").removeClass("btn-bgcolor-white");
				var Refundwithval = parseFloat($("#Refundwith-input").val());
				$(".Refundprice").text(Refundwithval);
				$("#Refundbtn").removeAttr("disabled");
			}
			return false;
		}
		</script>
		
		<?php echo $foot ?>
	</body>
</html>
