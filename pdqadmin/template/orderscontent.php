<!DOCTYPE html>
<html lang="en">
	<head>
		<title>orderscontents</title>
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
        <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myLargeModalLabel">Service Note</h4>
                    </div>
                  <?php  $attributes = array('id' => 'upa_complaint');?>
                  <?php echo form_open('ordersContent/addComplaints',$attributes); ?>
                    <div class="modal-body row">
                        <div class="form-group col-lg-12 col-ms-12 col-xs-12 nopadding">
                            
                                <div class="row">   
                                    <div class="form-group col-sm-6">
                                        <label for="">发货单：</label>
                                        <input type="hidden"  name="order_number" value="<?=$orders['order_number']?>" />
                                        <input type="hidden"  name="member_name" value="<?=$orders['member_name']?>" />
                                        <select name="send_bill" id="select_send" class="form-control">
                                          <option value="0">请选择发货单</option>
                                          <?php foreach ($complaint as $complaint): ?>
                                            <option value="<?=$complaint['send_bill']?>"><?=$complaint['send_bill']?></option>
                                          <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="">发货日期：</label>
                                        <input type="text" class="form-control" id="send_time" name="send_time" readonly value="请选择发货单" />
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="">问题物流方式：</label>
                                        <input type="text" class="form-control" id="logistics" name="logistics"  readonly value="请选择发货单" />
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="">跟踪号/单号：</label>
                                        <input type="text" class="form-control" id="track_code" name="track_code" readonly value="请选择发货单" />
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                           

                                <div class="form-group col-sm-12">
                                    <label for="bundlePrice">问题备注：</label>
                                    <textarea class="form-control" name="question_remark" rows="3"></textarea>
                                </div>

                                <div class="form-group col-sm-12">
                                    <label for="bundlePrice">退款备注：</label>
                                    <textarea class="form-control" name="refund_remark" rows="1"></textarea>
                                </div>


                                <div class="form-group col-sm-6">
                                    <label for="">问题分类：</label>
                                    <select name="question_type" class="form-control">
                                        <option value="0">错发</option>
                                        <option value="1">漏发</option>
                                        <option value="2">丢包</option>
                                        <option value="3">尺码问题</option>
                                        <option value="4">质量问题</option>
                                        <option value="5">物流超时</option>
                                        <option value="6">取消订单</option>
                                        <option value="7">退回中国</option>
                                        <option value="8">尚未发货</option>
                                        <option value="9">其他</option>
                                    </select>
                                </div>

                                <div class="form-group col-sm-6">
                                    <label for="">责任部门：</label>
                                    <select name="department" class="form-control">
                                        <option value="0">销售部</option>
                                        <option value="1">运营部</option>
                                        <option value="2">客服部</option>
                                        <option value="3">ERP</option>
                                        <option value="4">邮路</option>
                                        <option value="5">客户</option>
                                    </select>
                                </div>
                                                                    
                                <div class="col-xs-12 treatment">
                                    <div class="form-group col-sm-12 nopadding"><label for="">处理方式：</label></div>
                                    <div class="form-group col-xs-5 nopadding">
                                        <select name="dispose" class="form-control" id="treatment">
                                            <option value="0">发货</option>
                                            <option value="1">重寄</option>
                                            <option value="2">退款</option>
                                            <option value="3">退运费</option>
                                            <option value="4">退关税</option>
                                            <option value="5">Coupon</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-5">
                                        <input name="refund_amount" type="text" class="form-control" id="price" placeholder="$" style="display:none">
                                        <select name="coupon" class="form-control" id="coupon" style="display:none">
                                            <option value="1">Coupon10%</option>
                                            <option value="2">Coupon15%</option>
                                            <option value="3">Coupon20%</option>
                                            <option value="4">Coupon30%</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                         

                        </div>
                        <div class="row col-lg-12 col-ms-12 col-xs-12 text-right">
                            <button class="btn btn-ms btn-default" data-dismiss="modal">Close</button>
                            <button class="btn btn-ms btn-info" id="appendUser" type="submit">Save</button>
                        </div>
                        <div class="clearfix"></div>
                    </form>


                    </div>
                </div>
            </div>
        </div>
        
        
     
        <?php if($orders['order_risk']):?>
		<div class="modal fade" id="risk" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="myModalLabel">Risk Analysis</h4>
		      </div>
		      <div class="modal-body">
		        <div class="row">
		        	<div class="col-xs-4">
						<hr  <?= $orders['order_risk']==1?'class="low"':'class="org"' ?>/>
							<p align="center">LOW</p>
					</div>
					<div class="col-xs-4">
						<hr  <?= $orders['order_risk']==2?'class="medium"':'class="org"' ?>/>
							<p align="center">MEDIUM</p>
					</div>
					<div class="col-xs-4">
						 <hr  <?= $orders['order_risk']==3?'class="hight"':'class="org"' ?>/>
							<p align="center">HIGH</p>
					</div>
		        </div>
		        <div class="row">
		        	<div class="col-xs-12">
		        		<p class="risk-title"><strong>Risk indicators </strong></p>
		        	</div>
		        	<div class="col-xs-12">
		        	    
		        		<?php if($order_risk['riskScore']>10):?>
							<h6 class="ordercontent-iaddress-box"><i class="fa fa-warning ordercontent-iaddress hight-risk"></i>
						    	There is a high risk of this order being fraudulent. Pandacheer fraud analysis has detected details that appear suspicious. Contact the customer to validate the order. 
						    </h6>
						<?php endif ;?>
						        
						<?php if( $order_risk['ipAddressScore']>10):?>
						    <h6 class="ordercontent-iaddress-box"><i class="fa fa-warning ordercontent-iaddress hight-risk"></i>
						        The customer used a high risk Internet connection (web proxy) to place this order. 
						    </h6>
						<?php endif ;?>
						        
						<?php if($order_risk['creditCardCountry'] &&  $order_risk['creditCardCountry']!=$order_risk['shippingCountry']):?>
						    <h6 class="ordercontent-iaddress-box"><i class="fa fa-warning ordercontent-iaddress hight-risk"></i>
						        The credit card was issued in <?=$order_risk['creditCardCountry']?>, but the billing address country is <?=$order_risk['shippingCountry']?>.  
						    </h6>
	                    <?php endif ;?>
	                            
	                    <?php if($order_risk['shippingCountry']!=$order_risk['payCountry']):?>
	                        <h6 class="ordercontent-iaddress-box"><i class="fa fa-warning ordercontent-iaddress hight-risk"></i>
						        The billing address is listed as <?=$order_risk['shippingCountry']?>, but the order was placed from <?=$order_risk['payCountry']?>.
						    </h6>
	                    <?php endif ;?>
	                    
	                    <h6><i class="fa fa-map-marker ordercontent-imap"></i>Order placed from IP: <strong><?=$orders['ip_address']?></strong></h6>
		        	</div>
		        </div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      </div>
		    </div>
		  </div>
		</div>
		<?php endif; ?>

		
		

		<div id="wrapper">
			<?php echo $head ?>
			
			<div id="content">
				<div id="content-header" class="mini">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-lg-6">
							<h1><i class="glyphicon glyphicon-check" aria-hidden="true"></i>Orders</h1>
						</div>	
						<div class="col-xs-12 col-sm-6 col-lg-6">
						</div>	
				    </div>
					
				</div>
                <div id="breadcrumb">
                    <a href="/orders" title="Go to Order List" class="tip-bottom"><i class="fa fa-tags"></i>Orders</a>
                    <a class="current">Orders Content</a>
                </div>

                
                <?php if($orders['order_risk']):?>
                	<?php if($orders['order_risk']==3):?>
                <div class="row">
                	<div class="col-xs-12 col-sm-12 col-lg-12">
	                	<div class="alert alert-danger ordercontent-error" role="alert">
	                		<table>
	                			<tr>
	                				<td><i class="glyphicon glyphicon-info-sign hight-risk"></i></td>
	                				<td>
	                					<p>High risk of fraud detected</p>
	                                    <p>Before fulfilling this order or capturing payment, please review the <a data-toggle="modal" data-target="#risk">Risk Analysis </a>and determine if this order is fraudulent.</p>
	                				</td>
	                			</tr>
	                		</table>
	                	</div>
                	</div>
                </div>
                	<?php endif; ?>
                <?php endif; ?>
                
                
				<div class="row">
				
						
					<div class="col-xs-12 col-sm-8 col-lg-8">
					
						<div class="widget-box widget-box-hledit widget-box-hledit-ordercontent-left">
							<div class="widget-title">
								<h4 class="pull-left">Order Details</h4>
								<div class="clearfix"></div>
							</div>
							<div class="widget-content nopadding">
								<table class="table table-striped ordercontent-table">
									<tbody>
										<tr>
											<td width="10%">Item</td>
											<td width="40%"></td>
											<td width="15%">Price</td>
											<td width="10%">Qty</td>
											<td width="10%">actual_item_qty</td>
											<td width="30%">Sub-total</td>
                                        </tr>
                                        <?php foreach ($detail as $details): ?>
										<tr>
											<td width="12%">
											<a href="/product/edit/<?=$details['product_id']?>" target="_blank"><img class="ordercontent-image" src="<?=$details['image']?>" /></a>
											</td>
											<td width="40%">
                                            <a href="/product/edit/<?=$details['product_id']?>" target="_blank" class="ordercontent-table-tda"><?=htmlspecialchars_decode($details['product_name'])?></a>
											  <h6 class="subdued">
											    <?php if ($details['bundle_type']==1):?>
											      SKU: <?=$details['product_sku']?></h6>
											    <?php elseif($details['bundle_type']==2):?>
											       <?php 
											          $sku = explode(',',$details['product_sku']);
											          SKU: echo $sku[0];
											       ?>
											    <?php elseif($details['bundle_type']==3):?>
											       <?php 
											          $sku = explode(',',$details['product_sku']);
											           echo 'SKU:'.$sku[0].'<br/>SKU:'.$sku[1];
											       ?>
											    <?php endif; ?>
											</td>
											<td width="15%"><?= $this->session->userdata('my_currency') ?><?=$details['payment_price']/100?></td>
											<td width="10%"><?=$details['product_quantity']?></td>
											<td width="10%"><?=$details['total_qty']?></td>
											<td width="30%"><?= $this->session->userdata('my_currency') ?><?=$details['payment_amount']/100?></td>
										</tr>
                                      <?php endforeach; ?>


										<tr style="border-top:1px solid #EEE;">
											<td width="10%"></td>
											<td width="30%"></td>
											<td width="15%"></td>
											<td width="15%"></td>
											<td width="30%">
                                             
                                              
											  <table class="table--nested table--no-border type--right">
												  <tbody>
												  <tr>
													<td class="subdued">Subtotal</td>
													<td><?= $this->session->userdata('my_currency') ?><?=$orders['order_amount']/100?></td>
												  </tr>
												  <?php if ($orders['order_insurance']):?>  
												  <tr>
													<td class="subdued">Insurance</td>
													<td>
													  <?= $this->session->userdata('my_currency') ?><?=$orders['order_insurance']/100?>
													</td>
												  </tr>
												 <?php endif; ?>
												 <?php if ($orders['order_giftbox']):?>  
												  <tr>
													<td class="subdued">Giftbox</td>
													<td>
													  <?= $this->session->userdata('my_currency') ?><?=$orders['order_giftbox']/100?>
													</td>
												  </tr>
												 <?php endif; ?>
												 <?php if ($orders['coupons_id']):?>  
												  <tr>
													<td class="subdued">offers_amount</td>
													<td>
													    <?=$orders['coupons_id']?><br/>
													  -<?= $this->session->userdata('my_currency') ?><?=$orders['offers_amount']/100?>
													</td>
												  </tr>
												 <?php endif; ?>
												 <?php if ($refunds):?>  
                                                   <tr class="subdued">	
                                    			     <td>Refunded:</td>
                                    				 <td><?= $this->session->userdata('my_currency') ?><?=$amount/100 ?></td>
                                    			  </tr>
                                                  <?php endif; ?>
												  <tr>
													  <td class="subdued">
														<div><?=$shipping['express_type']?></div>
														<div><?=$append['order_weight']/100?> kg</div>
													  </td>
													  <td><?= $this->session->userdata('my_currency') ?><?=$orders['freight_amount']/100?></td>
												  </tr>
												  <tr class="next-heading">
														<td>Total</td>
														<td>
														  <?= $this->session->userdata('my_currency') ?><?=$orders['payment_amount']/100?>
														</td>
												  </tr>
												  </tbody>
											  </table>
											

											</td>
										</tr>
									</tbody>
								</table>	
							</div>
						</div>	
						
						
						<div class="widget-box widget-box-hledit widget-box-hledit-ordercontent-left">
							<div class="widget-title">
								<h4>Customer Note</h4>
							</div>
							<div class="widget-content nopadding">
								<form>
								  <div class="form-group">
									<label for="orderDetails">
										<?=$append['order_guestbook']?>
									</label>
								  </div>
								</form>
							</div>
						</div>	

                       
                       
                        <div class="widget-box widget-box-hledit widget-box-hledit-ordercontent-left order-tracking-content">
                            <div class="widget-title">
                                <h4>Service Note</h4>
                            </div>
                            <div class="widget-content">
                            <?php if ($order_complaints):?>
                                <div class="form-group">
                                    <table class="table table-hover">
                                    <tr>
                                        <th>发货单</th>
                                        <th>发货日期</th>
                                        <th>处理方式</th>
                                        <th>View</th>
                                    </tr>
                                    <tbody>
                                    <?php foreach ($order_complaints as $complaints): ?>
                                    <tr>
                                        <td width="20%"><?=$complaints['send_bill']?></td>
                                        <td width="20%"><?=date('Y-m-d H:i:s', $complaints['send_time'])?></td>
                                        <td width="20%"><?=$dispose[$complaints['dispose']]?></td>
                                        <td width="5%"><a href="/orderTrackingContent/<?=$complaints['complaints_id']?>"><button class="btn btn-default btn-sm"><i class="fa fa-eye fa-sm"></i></button></a></td>
                                    </tr>
                                    <?php endforeach ?>
                                    </tbody>
                                </table>
                                </div>
                            <?php endif; ?>
                                <button class="btn btn-default btn-bgcolor-white pull-right" data-toggle="modal" data-target=".bs-example-modal-lg" type="button"><i class="fa fa-plus fa-sm"></i> Add Service Note</button>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                      



						<div class="widget-box widget-box-hledit widget-box-hledit-ordercontent-left">
							<div class="widget-title">
								<h4>Additional Details</h4>
							</div>
							<div class="widget-content nopadding">

								
								<form id="upa_addOrderMemo">
								  <div class="form-group">
									<label for="orderDetails">Note</label>
                                        <input type="text" class="form-control" id="orderDetails" value="" name="memo" placeholder="Add a note to this order…">
                                        <div class="o_message">
                                        <?php if ($order_message):?>
                                        	<?php foreach ($order_message as $message): ?>
                                        		<p><?=$message?></p>
                                        	<?php endforeach ?>
                                        <?php endif; ?>
                                        </div>
                                        <input type="hidden"  value="<?=$orders['order_number']?>"  name="order_number"/>
								  </div>
								  <div class="pull-right">
								      <input type="button" id="addOrderMemo" class="btn btn-default" value="save" />
								  </div>
								  <div class="clearfix"></div>
								</form>

							</div>
						</div>	
						
						<div class="widget-box widget-box-hledit widget-box-hledit-ordercontent-left widget-box-hledit-ordercontent-specialleft">
							<div class="widget-title">
								<h4>History </h4>
							</div>
							<div class="widget-content nopadding">
							
                                <div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
                                    <ul id="myTab" class="nav nav-tabs ordercontent-tabnav" role="tablist">
                                      <li role="presentation" class="active"><a href="#home" id="home-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">All activity</a></li>
                                      <li role="presentation"><a href="#Fulfillments" role="tab" id="Fulfillments-tab" data-toggle="tab" aria-controls="Fulfillments">Refund</a></li>
                                    </ul>
                                    <div id="myTabContent" class="tab-content ordercontent-tabbox">
                                
                                      <div role="tabpanel" class="tab-pane fade in active" id="home" aria-labelledBy="home-tab">
                                
                                    <ul>
                                		<li>
                                			<div class="orderconetent-tablist"><i class="fa fa-lock"></i>
                                            	此订单付款方式为 <?= $orders['terminal']==1 ? '电脑端':'移动端'?>
                                            </div>
                                		</li>
                                	</ul>
                                
                                    <?php foreach ($log as $log): ?>
                                        <h4><?=date('Y-m-d H:i:s', $log['create_time'])?></h4>
                                		<ul>
                                			<li>
                                				<div class="orderconetent-tablist"><i class="fa fa-lock"></i>
                                                  <?=$orderStatus[$log['order_status']]?>___________________<?=$log['order_memo']?>___________________<?=$log['operator']?>
                                                </div>
                                			</li>
                                		</ul>
                                	<?php endforeach; ?>  
                                    
                                    
                                    <h4><?=date('Y-m-d H:i:s', $orders['create_time'])?></h4>
                                		<ul>
                                			<li>
                                				<div class="orderconetent-tablist"><i class="fa fa-lock"></i>
                                                   	此订单付款方式为 : <?= $orders['pay_type']==1 ? 'paypal_____'.$orders['transaction_id'] : '信用卡_____'.$orders['transaction_id'] ;?>
                                                </div>
                                			</li>
                                	</ul>
                                      
                                
                                     </div>
                                
                                      <div role="tabpanel" class="tab-pane fade" id="Fulfillments" aria-labelledBy="Fulfillments-tab">
                                
                                        <?php if ($refunds):?>
                                          <?php foreach ($refunds as $refund): ?>
                                	      <h4>
                                		    <?=date('Y-m-d H:i:s', $refund['create_time'])?>
                                		  </h4>
                                		  <ul>
                                		   <li>
                                			 <div class="orderconetent-tablist">
                                				<i class="fa fa-lock ordercontent-icolor">
                                				 </i>此订单退货金额为<a href="/orderRefund/getInfo/<?=$refund['refund_id']?>">$<?=$refund['refund_amount']/100?></a>
                                				 <?php if ($refund['refund_status']==1):?>
                                				 	 ----------退款单状态：未处理,申请人:<?=$refund['proposer_name']?>
                                				 <?php elseif($refund['refund_status']==2): ?>
                                				 	 ----------退款单状态：已退款,申请人:<?=$refund['proposer_name']?>--------退款执行者:<?=$refund['operator']?>
                                				 <?php else: ?>
                                				     ----------退款单状态：已取消,申请人:<?=$refund['proposer_name']?>-------- 执行者:<?=$refund['operator']?>
                                				 <?php endif; ?>
                                			 </div>
                                		   </li>
                                		 </ul>
                                		 <?php endforeach; ?>
                                       <?php endif; ?>
                                
                                      </div>
                                	   
                                    </div>
                                  </div><!-- /example -->
							</div>
						</div>	
						
					</div>
					<div class="col-xs-12 col-sm-4 col-lg-4">

					   <div class="widget-box widget-box-hledit widget-box-hledit-ordercontent-right">
                            <div class="widget-title">
								<h4 class="productcontent-seotitle">Fulfilment</h4>
							</div>

                            
                            <?php if ($is_send):?>
                                <?php foreach ($arr_send as $key=>$value): ?>
                                <div class="widget-box-hledit-ordercontent-right-border clearfix">
                                    <div class="row">
                                        <div class="widget-title col-sm-3 col-xs-3">
                                           <?php if ($key==1):?>
                                             <h5>Original</h5>
                                           <?php else: ?>
                                             <h5>Resend#<?=$key-1?></h5>
                                           <?php endif; ?>
                                        </div>
                                        <div class="widget-content nopadding col-sm-6 col-xs-6">
                                            <select class="form-control" disabled="disabled">
                                                <option><?=$sysSendStatus[$value[count($value)-1]['send_status']]?></option>
                                            </select>
                                        </div>
                                    </div>
                                  
                                <?php foreach ($value as $k=> $v): ?>
                                    <?php if ($v['track_url']):?>
                                    <div class="row">
                                        <div class="widget-title col-sm-3 col-xs-3">
                                            <h5>Track#<?=$k+1?></h5>
                                        </div>  
                                        <div class=" col-sm-9 col-xs-9"> 
                                            <h5><a  href="<?=$v['track_url']?>"><?=$v['track_code']?></a></h5>
                                        </div>
                                    </div>
                                    <?php else: ?>
                                    <div class="row">
                                        <div class="widget-title col-sm-3 col-xs-3">
                                            <h5>Track#<?=$k+1?></h5>
                                        </div>  
                                        <div class="widget-content col-sm-9 col-xs-9"> 
                                            <button type="button" class="btn btn-success">Dispatched</button>
                                        </div>
                                    </div>                                               
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                      





							<div class="widget-box-hledit-ordercontent-right-border clearfix">
    							<div class="row">
    								<div class="widget-title col-sm-3 col-xs-3">
                                    <?php if ($is_send):?>
    									<h5>Resend#<?=$orders['is_resend']-1?></h5>
                                    <?php else: ?>
                                        <h5>Original</h5>
                                    <?php endif; ?>
    								</div>
    								<div class="widget-content nopadding col-sm-6 col-xs-6">
    									<select class="form-control" disabled="disabled">
    									   <option ><?=$sysSendStatus[$orders['send_status']]?></option>
    									</select>
    								</div>
    								<div class="pull-right col-sm-3 col-sm-3">
                                    
    								  <?php if ($orders['send_status'] != 1):?>
    									<button type="button" class="btn btn-default btn-bgcolor-white" data-toggle="modal" data-target=".produccontent-button-Fulfilldialog">Fulfil</button>
    							      <?php endif; ?>   		
    									<div class="modal produccontent-button-Fulfilldialog" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
    										<div class="modal-dialog">
    										  <div class="modal-content">
    											<div class="modal-header">
    											  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    											      <h4 class="modal-title" id="gridSystemModalLabel">Fulfill</h4>
    											</div>
    
    									     <?php echo form_open('ordersContent/send_success'); ?>
    											<div class="modal-body">
    											  <div class="container-fluid">
    												<div class="row Reasonrefundbox">
    													  <div class="form-group">
    													    <label for="TrackingURL">Action </label>
        												    <select class="form-control" id="fulfill" name="send_status">
                                                                <option value="1">Fulfilled</option>
                                                                <option value="2">Partially Fulfilled</option>
                                                                <option value="3">Dispatched</option>
        									                </select>
    													  </div>
    
    													  <input type="hidden" value="<?=$orders['order_number']?>" name="order_number" />
    													  <div class="form-group" id="track_name">
    														<label for="TrackingName">Tracking Namer</label>
    														<input type="text" class="form-control" name="express_name" placeholder="Tracking Name">
    													  </div>
    													  <div class="form-group" id="track_num">
    														<label for="TrackingNumber">Tracking Number</label>
    														<input type="text" class="form-control" name="express_code" placeholder="Tracking Number">
    													  </div>
    													  <div class="form-group" id="track_url">
    														<label for="TrackingURL">Tracking URL </label>
    														<input type="text" class="form-control" name="express_url" placeholder="Tracking URL">
    													  </div>
    												</div>
    											  </div>
    											</div>
    											<div class="modal-footer">
    											  <button type="button" class="btn btn-default btn-bgcolor-white" data-dismiss="modal">Cancel</button>
    											     <!-- <input type="submit"  value="Fulfill"  class="btn btn-default btn-bgcolor-blue"/> -->
    											</div>
    										</form>	
    
    										  </div><!-- /.modal-content -->
    										</div><!-- /.modal-dialog -->
    									</div><!-- /.modal -->  	
    									
    								</div>
								</div>
                                
                            <?php if ($track):?>
                                <?php foreach ($track as $k => $v): ?>
                                    <?php if ($v['track_url']):?>
                                    <div class="row">
                                        <div class="widget-title col-sm-3 col-xs-3">
                                            <h5>Track#<?=$k+1?></h5>
                                        </div>
                                        <div class=" col-sm-6 col-xs-6">
                                            <h5><a href="<?=$v['track_url']?>"><?=$v['track_code']?></a></h5>
                                        </div>
                                        <div class="widget-content col-sm-3 col-xs-3">
                                        </div>
                                    </div>
                                    <?php else: ?>
                                    <div class="row">
                                        <div class="widget-title col-sm-3 col-xs-3">
                                            <h5>Track#<?=$k+1?></h5>
                                        </div>  
                                        <div class="widget-content col-sm-9 col-xs-9"> 
                                            <button type="button" class="btn btn-success">Dispatched</button>
                                        </div>
                                    </div>
                                    <?php endif; ?>           
                                <?php endforeach ?>
                            <?php endif; ?>


            

				            </div>
                           
                            <?php if ($orders['send_status'] != 0):?>
                            <?php echo form_open('ordersContent/addRedirect'); ?>
                            <input type="hidden"  name="order_number" value="<?=$orders['order_number']?>" />
                            <div class="widget-box-hledit-ordercontent-right-border clearfix">
								<div class="widget-title col-sm-3 col-xs-3">
									<h5>Resend</h5>
								</div>
								<div class="widget-content col-sm-6 col-xs-6">
                                <a href="#"><button type="submit" class="btn btn-default btn-bgcolor-white">Resend This Order</button></a>
								</div>
                                <div class="widget-content col-sm-3 col-xs-3">
    				            </div>
                            </div>
                            </form>
                            <?php endif;?> 

						</div>
						
						
						<div class="widget-box widget-box-hledit widget-box-hledit-ordercontent-right">
                            <div class="widget-title">
								<h4 class="productcontent-seotitle">Status</h4>
							</div>
                            <div class="widget-box-hledit-ordercontent-right-border">
								<div class="widget-title col-sm-3 col-xs-3">
									<h5>Payment</h5>
								</div>
								<div class="widget-content nopadding col-sm-6 col-xs-6">
									<select class="form-control" disabled="disabled">
									   <option ><?=$sysPayStatus[$orders['pay_status']]?></option>
									</select>
								</div>
								<div class="pull-right col-sm-3 col-xs-3">
									<?php if ($orders['pay_status'] != 2):?>
                                      <button type="button" class="btn btn-default btn-bgcolor-white" data-toggle="modal" data-target=".produccontent-button-Refunddialog">Refund</button>
                                    <?php endif; ?> 
                                    <div class="modal produccontent-button-Refunddialog" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
                                    	<div class="modal-dialog">


                                    	  <div class="modal-content">
                                    		<div class="modal-header">
                                    		  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    		  <h4 class="modal-title" id="gridSystemModalLabel">Refund Payments</h4>
                                    		</div>
                                    
                                           
                                           <?php echo form_open('ordersContent/add_refund'); ?>
                                           <input type="hidden"  name="order_number" value="<?=$orders['order_number']?>" />
                                           <input type="hidden"  name="refund_status" value="1" />
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
                                    					       <input name="re_quantity[]" value="0" size="4" title="Qty" class="input-text qty quantity-valinput" maxlength="12" readonly>
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
                                    					  <td><?= $this->session->userdata('my_currency') ?><span id="total"><?=($orders['payment_amount']-$orders['order_insurance']-$orders['order_giftbox'])/100-$amount/100?></span></td>
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
                                    							  	 <input type="text" class="form-control Refundwith-input" id="Refundwith-input" value="" placeholder="0.00" name="re_amount">
                                    							</div>
                                    					  </td>
                                    					</tr>
                                    				</tbody>
                                    			</table>								
                                    			<div class="row Reasonrefundbox">
                                                <div class="col-xs-6">
                                                    <label for="exampleInputEmail1">Reason for Return</label>
                                                    <select class="selectpicker" data-width="90%" name="re_reason">
                                                        <option value="Faulty">Faulty</option>
                                                        <option value="Damaged">Damaged</option>
                                                        <option value="Incorrect">Incorrect</option>
                                                        <option value="Stockout">Stockout</option>
                                                        <option value="Change of Mind">Change of Mind</option>
                                                    </select>
                                                </div>
                                    			<div class="col-xs-6">
                                                    <label for="exampleInputEmail1">Preferred Resolution</label>
                                                    <select class="selectpicker" data-width="90%" name="re_resolution">
                                                        <option value="Store Credit">Store Credit</option>
                                                        <option value="Cash Refund">Cash Refund</option>
                                                    </select>
                                                </div>   
						                        <div class="col-xs-12">
                                                    <label for="Reasonrefund"><b>Reason for refund (optional)</b></label>
                                                    <input type="text" class="form-control" id="Reasonrefund" name="re_details">              
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
                                    		  <?php if ($orders['pay_status'] != 2 && empty($is_untreated) && !$is_Apply):?>
                                    		    <button type="submit" class="btn btn-default btn-bgcolor-white" id="Refundbtn">Refund</button>
                                    		  <?php endif; ?>
                                    		</div>
                                    
                                    
                                    	  </div><!-- /.modal-content -->
                                         </form>

                                    	</div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->  										
										
								</div>
								<div class="clearfix"></div>
							 </div>	
                            <div class="widget-box-hledit-ordercontent-right-border">
							    <form id="upa_form">
							    <input type="hidden"  name="order_number" value="<?=$orders['order_number']?>" />
								<div class="widget-title col-sm-3 col-xs-3">
									<h5>Archive</h5>
								</div>
								<div class="widget-content nopadding col-sm-6 col-xs-6">
									<select class="form-control" name="archive">								  
									  <option value="1" <?php echo $orders['doc_status']==1 ? 'selected=selected' : '';?> >processing</option>
									  <option value="2" <?php echo $orders['doc_status']==2 ? 'selected=selected' : '';?> >finished</option>
									  <option value="3" <?php echo $orders['doc_status']==3 ? 'selected=selected' : '';?> >cancle</option>
									</select>
								</div>
								<div class="pull-right col-sm-3 col-xs-3">
								    <input type="button" id="up_archive"  value="save" class="btn btn-default btn-bgcolor-white" />
								</div>
								<div class="clearfix"></div>
								</form>
						     </div>
						     
						     
						     <div class="widget-box-hledit-ordercontent-right-border">
							    <form id="upa_form2">
							    <input type="hidden"  name="order_number" value="<?=$orders['order_number']?>" />
								<div class="widget-title col-sm-3 col-xs-3">
									<h5>Status</h5>
								</div>
								<div class="widget-content nopadding col-sm-6 col-xs-6">
									<select class="form-control" name="order_status">								  
									  <option value="1" <?php echo $orders['order_status']==1 ? 'selected=selected' : '';?> >open</option>
									  <option value="2" <?php echo $orders['order_status']==2 ? 'selected=selected' : '';?> >closed</option>
									</select>
								</div>
								<div class="pull-right col-sm-3 col-xs-3">
								    <input type="button" id="up_status"  value="save" class="btn btn-default btn-bgcolor-white" />
								</div>
								<div class="clearfix"></div>
								</form> 
						     </div>
						        	
						</div>						
						
						<div class="widget-box widget-box-hledit widget-box-hledit-ordercontent-right">
                            <div class="widget-title">
								<h4 class="productcontent-seotitle">Additional Service</h4>
							</div>
                            <p>Insurance : <?php echo $orders['order_insurance'] ? 'Yes' : 'No';?></p>
                            <p>Gift Box : <?php echo $orders['order_giftbox'] ? 'Yes' : 'No';?></p>
						</div>
	
						<div class="widget-box widget-box-hledit widget-box-hledit-ordercontent-right">
							<div class="ordercontent-right-map">
								<h4 class="ordercontent-right-map-nameh4"><a href="/customers/getInfo/<?=$orders['member_id']?>"><?=$orders['member_name']?></a></h4>
							</div>
							<div class="ordercontent-right-map ordercontent-right-mapaddress">
								<h5 class="ordercontent-right-mapaddress-span"><span>Shipping address </span>
								
                                    <a href="#" data-toggle="modal" data-target=".produccontent-buttonShipping-editdialog">edit</a>
                                    <div class="modal produccontent-buttonShipping-editdialog" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
                                    	<div class="modal-dialog">
                                    	  <div class="modal-content">
                                    		<div class="modal-header">
                                    		  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    		  <h4 id="gridSystemModalLabel">Edit Address</h4>
                                    		</div>
                                    		<div class="modal-body">
                                    		  <div class="container-fluid">
                                    			<div class="row">
                                    
                                    			  <?php echo form_open('ordersContent/editOrderShip'); ?>
                                    			      <input type="hidden"  value="<?=$shipping['order_number']?>"  name="order_number"/>
                                    			      <div class="form-inline-box">
                                    					  <div class="form-group">
                                    						<label for="Customername">First name</label>
                                    						<input type="text" class="form-control"  name="receive_firstName" value="<?=$shipping['receive_firstName']?>">
                                    					  </div>
                                                          <div class="form-group">
                                                            <label for="Customername">Last name</label>
                                                            <input type="text" class="form-control"  name="receive_lastName" value="<?=$shipping['receive_lastName']?>">
                                                          </div>
                                    				  </div>
                                                     
                                                       <div class="form-group">
                                                        <label for="Companyinput">Company</label>
                                                        <input type="text" class="form-control"  name="receive_company" value="<?=$shipping['receive_company']?>">
                                                      </div>
                                                  
                                    				  <div class="form-group">
                                    					<label for="Address1input">Address1</label>
                                    					<input type="text" class="form-control"  name="receive_add1" value="<?=$shipping['receive_add1']?>">
                                    				  </div>
                                    				  <div class="form-group">
                                    					<label for="Address2input">Apt,Suite,etc</label>
                                    					<input type="text" class="form-control"  name="receive_add2" value="<?=$shipping['receive_add2']?>">
                                    				  </div>
                                    				  <div class="form-inline-box">
                                    					  <div class="form-group">
                                    						<label for="Cityinput">City</label>
                                    						<input type="text" class="form-control"  name="receive_city" value="<?=$shipping['receive_city']?>">
                                    					  </div>
                                    					  <div class="form-group">
                                    						<label for="Zipinput">Zip / Postal code</label>
                                    						<input type="text" class="form-control"  name="receive_zipcode" value="<?=$shipping['receive_zipcode']?>">
                                    					  </div>
                                    				  </div>
                                    				   <div class="form-inline-box">	  
                                    					  <div class="form-group">
                                    						<label for="exampleInputEmail1">Country</label>
                                                            <input type="text" class="form-control"  name="receive_country" value="<?=$shipping['receive_country']?>">
                                    					  </div>
                                    					  <div class="form-group">
                                    						<label for="Provinceinput">Province</label>
                                    						<input type="text" class="form-control"   name="receive_province" value="<?=$shipping['receive_province']?>">
                                    					  </div>
                                    					  <div class="form-group">
                                    						<label for="Phoneinput">Phone</label>
                                    						<input type="text" class="form-control"  name="receive_phone" value="<?=$shipping['receive_phone']?>">
                                    					  </div>
                                    				 </div>	  
                                    			</div>
                                    		  </div>
                                    		</div>
                                    		<div class="modal-footer">
                                    		  <button type="button" class="btn btn-default btn-bgcolor-white" data-dismiss="modal">Cancel</button>
                                    		  <input type="submit" value="Save" class="btn btn-primary btn-bgcolor-blue" />
                                    		</div>
                                    	 </form>
                                    
                                    
                                    
                                    	  </div><!-- /.modal-content -->
                                    	</div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->  												
								
								</h5>
								
								
								<h6><?=$shipping['receive_firstName']?> <?=$shipping['receive_lastName']?></h6>
								<h6><?=$shipping['receive_company']?></h6>
								<h6><?=$shipping['receive_add2']?> <?=$shipping['receive_add1']?></h6>
								<h6><?=$shipping['receive_city']?>,<?=$shipping['receive_province']?>,<?=$shipping['receive_zipcode']?></h6>
								<h6><?=$shipping['receive_country']?></h6>
								<h6><?=$shipping['receive_phone']?></h6>
							</div>
							
							<div class="ordercontent-right-map ordercontent-right-mapaddress">
								<h5 class="ordercontent-right-mapaddress-span"><span>billing address</span>
								
                                <a href="#" data-toggle="modal" data-target=".produccontent-buttonbilling-editdialog">edit</a>
                                <div class="modal produccontent-buttonbilling-editdialog" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
                                	<div class="modal-dialog">
                                	  <div class="modal-content">
                                		<div class="modal-header">
                                		  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                		  <h4 id="gridSystemModalLabel">Edit Address</h4>
                                		</div>
                                		<div class="modal-body">
                                		  <div class="container-fluid">
                                			<div class="row">
                                
                                
                                			  <?php echo form_open('ordersContent/editOrderBill'); ?>
                                			      <input type="hidden"  value="<?=$billing['order_number']?>"  name="order_number"/>
                                			      <div class="form-inline-box">
                                					  <div class="form-group">
                                						<label for="Customername">First name</label>
                                						<input type="text" class="form-control" name="receive_firstName"  value="<?=$billing['receive_firstName']?>" >
                                					  </div>
                                                      <div class="form-group">
                                                        <label for="Customername">Last name</label>
                                                        <input type="text" class="form-control" name="receive_lastName"  value="<?=$billing['receive_lastName']?>" >
                                                      </div>
                                				  </div>
                                                  <div class="form-group">
                                                        <label for="Companyinput">Company</label>
                                                        <input type="text" class="form-control" name="receive_company" value="<?=$billing['receive_company']?>">
                                                      </div>
                                				  <div class="form-group">
                                					<label for="Address1input">Address1</label>
                                					<input type="text" class="form-control" name="receive_add1" value="<?=$billing['receive_add1']?>">
                                				  </div>
                                				  <div class="form-group">
                                					<label for="Address2input">Apt,Suite,etc</label>
                                					<input type="text" class="form-control" name="receive_add2" value="<?=$billing['receive_add2']?>">
                                				  </div>
                                				  <div class="form-inline-box">
                                					  <div class="form-group">
                                						<label for="Cityinput">City</label>
                                						<input type="text" class="form-control" name="receive_city" value="<?=$billing['receive_city']?>">
                                					  </div>
                                					  <div class="form-group">
                                						<label for="Zipinput">Zip / Postal code</label>
                                						<input type="text" class="form-control" name="receive_zipcode" value="<?=$billing['receive_zipcode']?>">
                                					  </div>
                                				  </div>
                                				   <div class="form-inline-box">	  
                                					  <div class="form-group">
                                						<label for="exampleInputEmail1">Country</label>
                                						<input type="text" class="form-control" name="receive_country"   value="<?=$billing['receive_country']?>">
                                					  </div>
                                					  <div class="form-group">
                                						<label for="Provinceinput">Province</label>
                                						<input type="text" class="form-control" name="receive_province"   value="<?=$billing['receive_province']?>">
                                					  </div>
                                					  <div class="form-group">
                                						<label for="Phoneinput">Phone</label>
                                						<input type="text" class="form-control" name="receive_phone" value="<?=$billing['receive_phone']?>">
                                					  </div>
                                				 </div>
                                			</div>
                                		  </div>
                                		</div>
                                		<div class="modal-footer">
                                		  <button type="button" class="btn btn-default btn-bgcolor-white" data-dismiss="modal">Cancel</button>
                                		  <input type="submit" value="Save" class="btn btn-primary btn-bgcolor-blue" />
                                		</div>
                                      </form>
                                
                                	  </div><!-- /.modal-content -->
                                	</div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->  												
								
								</h5>
								
								
								<h6><?=$billing['receive_firstName']?> <?=$billing['receive_lastName']?></h6>
								<h6><?=$billing['receive_company']?></h6>
								<h6><?=$billing['receive_add2']?> <?=$billing['receive_add1']?></h6>
								<h6><?=$billing['receive_city']?>,<?=$billing['receive_province']?>,<?=$billing['receive_zipcode']?></h6>
								<h6><?=$billing['receive_country']?></h6>
								<h6><?=$billing['receive_phone']?></h6>
							</div>
							
							
							
							<div class="ordercontent-right-map">
								<h5 class="ordercontent-right-map-h5">
									<i class="fa fa-envelope-o fa-fw ordercontent-right-map-istyle"></i>
									<a href="/customers/getInfo/<?=$orders['member_id']?>" class="ordercontent-right-map-a"><?=$orders['member_email']?></a>
								</h5>
							</div>
							<div class="ordercontent-right-map ordercontent-right-mapShipping">
							    <i class="fa fa-truck ordercontent-right-map-istyle"></i>
								<div class="ordercontent-right-mapShipping-method">
									<h6>Shipping method: <strong><?=$shipping['express_type']?></strong></h6>
									<h6>Total weight: <strong><?=$append['order_weight']?> g</strong></h6>
								</div>
							</div>
						</div>
						
						<div class="widget-box widget-box-hledit widget-box-hledit-ordercontent-right">
							<div class="widget-title">
								<h4 class="productcontent-seotitle">Risk Analysis</h4>
							</div>
							
							
				
						    <?php if($orders['order_risk']):?>
							<div class="widget-content nopadding" id="risk-right">
								<h6 class="divider-slim">The following risks were assessed for this order:</h6>
								<div class="row">
						        	<div class="col-xs-4">
						        		<hr  <?= $orders['order_risk']==1?'class="low"':'class="org"' ?>/>
										<p align="center">LOW</p>
						        	</div>
						        	<div class="col-xs-4">
						        		<hr  <?= $orders['order_risk']==2?'class="medium"':'class="org"' ?>/>
										<p align="center">MEDIUM</p>
						        	</div>
						        	<div class="col-xs-4">
						        		<hr  <?= $orders['order_risk']==3?'class="hight"':'class="org"' ?>/>
										<p align="center">HIGH</p>
						        	</div>
						        </div>
						        
						        <?php if( $order_risk['riskScore']>10):?>
						        	<h6 class="ordercontent-iaddress-box"><i class="fa fa-warning ordercontent-iaddress hight-risk"></i>
						        		There is a high risk of this order being fraudulent. Pandacheer fraud analysis has detected details that appear suspicious. Contact the customer to validate the order. 
						           	</h6>
						        <?php endif ;?>
						        
						        <?php if( $order_risk['ipAddressScore']>10):?>
						        	<h6 class="ordercontent-iaddress-box"><i class="fa fa-warning ordercontent-iaddress hight-risk"></i>
						        		The customer used a high risk Internet connection (web proxy) to place this order. 
						           	</h6>
						        <?php endif ;?>
						        
						        <?php if( $order_risk['creditCardCountry'] &&  $order_risk['creditCardCountry']!=$order_risk['shippingCountry']):?>
						        	<h6 class="ordercontent-iaddress-box"><i class="fa fa-warning ordercontent-iaddress hight-risk"></i>
						        		The credit card was issued in <?=$order_risk['creditCardCountry']?>, but the billing address country is <?=$order_risk['shippingCountry']?>.  
						           	</h6>
	                            <?php endif ;?>
	                            
	                            <?php if($order_risk['shippingCountry']!=$order_risk['payCountry']):?>
	                            	<h6 class="ordercontent-iaddress-box"><i class="fa fa-warning ordercontent-iaddress hight-risk"></i>
						        		The billing address is listed as <?=$order_risk['shippingCountry']?>, but the order was placed from <?=$order_risk['payCountry']?>.
						           	</h6>
	                            <?php endif ;?>
	
								<h6><i class="fa fa-map-marker ordercontent-imap"></i>Order placed from IP: <strong><?=$orders['ip_address']?></strong></h6>
								<h6><a data-toggle="modal" data-target="#risk">View full risk analysis</a></h6>
							</div>
							
							<?php else:?>
							    <h6><i class="fa fa-map-marker ordercontent-imap"></i>Order placed from IP: <strong><?=$orders['ip_address']?></strong></h6>
							<?php endif ;?>
							
							
			
							
							
						</div>	
						
						<div class="widget-box widget-box-hledit widget-box-hledit-ordercontent-right">
							<div class="widget-title">
								<h4 class="productcontent-seotitle">Conversion</h4>
							</div>
							<div class="widget-content nopadding">
								<h6>Landing page</h6>
								<h6><a class="plain" href="<?=$append['landing_page']?>" target="_blank"><?=$append['landing_page']?></a></h6>
								
								<h6>Referal Site</h6>
								<h6><a class="plain" href="<?=$append['refer_site']?>" target="_blank"><?=$append['refer_site']?></a></h6>
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
        <!--左侧nav-->
        <script src="js/jquery.nicescroll.min.js"></script>
        <script src="js/unicorn.js"></script>
         <script src="js/jquery.notifyBar.js"></script>
        <?php echo $foot ?>

        <script>
        //修改问题分类
            $('.selectpicker').select2();
            $('.order-traking-content-q input').focus(function(){
                this.blur();
            });
            $('.order-traking-content-q .fa-pencil').on('click',function(){                 
                $num = $('.order-traking-content-q .fa-pencil').index(this);
                $('.order-traking-content-q select').eq($num).fadeIn(200);
            });
            $('.order-traking-content-q select').on('change',function(){
                $num = $('.order-traking-content-q select').index(this);
                $('.order-traking-content-q input').eq($num).val($(this).val());
            });



            
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
            $('.treatment .fa-pencil').on('click',function(){
                $('#treatment').attr('disabled',false);
            })


        $('#myTab a').click(function (e) {
          e.preventDefault();
          $(this).tab('show');
        })
        
        
        //退款金额对比
        // $('#Refundwith-input').blur(function(){
        //     $Refundwith = Number($('#Refundwith-input').val());
        //     $Total = Number($("#total").text());
        //     if ($Refundwith > $Total) {
        //         alert('Error');
        //         $(this).focus();
        //     };
        // });
        $('#Refundbtn').on('click',function(){
            $Refundwith = Number($('#Refundwith-input').val());
            $Total = Number($("#total").text());
            if ($Refundwith > $Total) {
                alert('Error');
                $('#Refundwith-input').focus();
                return false;
            };
        });

        //购买产品数量函数
        function qtyDown(sku){
            var qty = $('.'+sku).find('.quantity-valinput').val();
        	if(qty <=0) return false;
            qty--;
        	$('.'+sku).find('.quantity-valinput').val(qty)
        	var Paymentspricetext = Number($('.'+sku).find(".Payments-modal-price").text());
        	if( !isNaN( qty ) && qty >= 0 ){ 

                var itemValue = Number(Paymentspricetext);
        		var totalValue = Number($("#Refundwith-input").val()); 
        		totalValue = totalValue -itemValue;
                $("#Refundwith-input").val(totalValue.toFixed(2));

        	}
        	return false;
        }
        
        function qtyUp(sku){
            var qty = $('.'+sku).find('.quantity-valinput').val();

        	var quantityval = Number($('.'+sku).find(".quantityval").text())-Number($('.'+sku).find(".refund-qty").text()); 
        	var Paymentspricetext = $('.'+sku).find(".Payments-modal-price").text();
        	if( !isNaN( qty ) && qty < quantityval) { 
        		qty++;
                $('.'+sku).find('.quantity-valinput').val(qty)
        		//$("#Refundwith-input").val((Paymentspricetext*(parseFloat($("#quantity-valinput").val())))).toFixed(2);
                
                var itemValue = Number(Paymentspricetext);
        		var totalValue = Number($("#Refundwith-input").val()); 
        		totalValue = totalValue +itemValue;
                $("#Refundwith-input").val(totalValue.toFixed(2));

        	}
        	return false;
        }

		$(function () {
			$("#fulfill").change(function(){
				if($("#fulfill").val()==3){
					$("#track_name").hide();
					$("#track_num").hide();
					$("#track_url").hide();
        		}else{
        			$("#track_name").show();
           			$("#track_num").show();
           			$("#track_url").show();
        		}
      		});



            $("#select_send").change(function(){
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('ordersContent/get_complaint') ?>",
                    dataType: 'json',
                    data: $("#upa_complaint").serialize(),
                    success: function (result) {
                        if(result){
                            $("#track_code").val(result.track_code);
                            $("#send_time").val(result.send_time);
                            $("#logistics").val(result.logistics);
                        }else{
                           alert(result); 
                        }
                       
                    }
                });
            }); 
   




            $("#up_archive").on("click", function () {
				$.ajax({
                    type: "POST",
                    url: "<?php echo site_url('ordersContent/updateArchive') ?>",
                    dataType: 'json',
                    data: $("#upa_form").serialize(),
                    success: function (result) {
                    	if(result){
                    		$.notifyBar({cssClass: "dg-notify-success", html: "修改成功", position: "bottom"});
                    	}else{
                    		$.notifyBar({cssClass: "dg-notify-error", html: "修改失败", position: "bottom"});
 
                    	}	 
                    }
                });
            });



            $("#up_status").on("click", function () {
				$.ajax({
                    type: "POST",
                    url: "<?php echo site_url('ordersContent/updateStatus') ?>",
                    dataType: 'json',
                    data: $("#upa_form2").serialize(),
                    success: function (result) {
                    	if(result){
                    		$.notifyBar({cssClass: "dg-notify-success", html: "修改成功", position: "bottom"});
                    	}else{
                    		$.notifyBar({cssClass: "dg-notify-error", html: "修改失败", position: "bottom"});
                    	}	 
                    }
                });
            });

            



            $("#addOrderMemo").on("click", function () {
                if ($('#orderDetails').val()==''){

                    alert('Additional Details必填');
                    return false;
                }else{
                    $.ajax({
                        type: "POST",
                        url: "<?php echo site_url('ordersContent/addOrderMemo') ?>",
                        dataType: 'json',
                        data: $("#upa_addOrderMemo").serialize(),
                        success: function (result) {
                            if(result){
                                $(".o_message").append("<p>"+result+"</p>");
                            }else{
                                alert(result);
                            }
                        }
                    });
                }

            });
        });

        </script>
	</body>
</html>
