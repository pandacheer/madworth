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
                            <h1><i class="glyphicon glyphicon-usd" aria-hidden="true"></i>Refund List</h1>
                        </div>
                    </div>	
                </div>
                <div class="row">
                     
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="widget-box widget-box-hledit widget-box-hledit-order-left">
                            
                        <?php echo form_open('orderRefund'); ?>
                          <div class=" order_search row">
                          	 <div class="col-sm-3">
                          	 	<select name="s_status" class="form-control">
                                            <option value=""<?php if(empty($where[1]))echo " selected";?>>请选择...</option>
                          	 	    <option value="order_number" <?php if($where[1] == 'order_number')echo " selected";?>>订单号码</option>
                          	 	</select>
                          	 </div>
						     <div class="input-group col-sm-6 nopadding">
							   <input type="text" id="orderRefund-search" class="form-control" value="<?php if($where[0]!='ALL')echo $where[0];?>" name="search" placeholder="Search for..." >
							   <span class="input-group-btn">
							       <input type="submit"  id="orderRefund-submit" class="btn btn-default" value="Go!" />
							   </span>
							  </div>
						    </div>
                        </form>
                            <?php if (empty($refund_bills)):?><span> No GET data exists </span>
                               <?php else: ?>
                                <div class="widget-content nopadding">
                                    <table class="table table-striped table-hover Customerslist-table order-refund-list">
                                        <thead>
                                            <tr>
                                                <th>refund</th>
                                                <th>Order</th>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Operator</th>
                                                 <th>Operation</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                          <?php foreach ($refund_bills as $refund): ?>
                                            <tr>
                                                <td><a href="/orderRefund/getInfo/<?=$refund['refund_id']?>"><?=$refund['refund_id']?></a></td>
                                                <td><a href="/ordersContent/<?=$refund['order_number']?>"><?=$refund['order_number']?></a></td>
                                                <td><?=date('Y-m-d H:i:s', $refund['create_time'])?></td>
                                                <td>
                                                  <?php if($refund['refund_status']==1):?> 未处理
                                                  <?php elseif($refund['refund_status']==2): ?> 已退款
                                                  <?php else: ?> 已取消
                                                  <?php endif; ?>
                                                </td>
                                                <td><?=$refund['proposer_name']?>
                                                <td>
                                                <?php if($refund['refund_status']!=2):?>
                                                  <button class="delete_refund btn btn-default" value="<?=$refund['refund_id']?>"><i class="fa fa-trash-o fa-lg"></i></button>
                                                <?php endif; ?>
                                               </td> 
                                            </tr>  
                                          <?php endforeach; ?>                                                           
                                        </tbody>
                                    </table>
                                   <?php endif; ?>
                                </div>   
                            </div>

                            <ul class="pagination alternate">
                              <?php if (isset($pages)) echo $pages ?>
                            </ul>
                        </div>		
                    </div>
                    <div class="row">
                        <div id="footer" class="col-xs-12"></div>
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
        <script src="js/jquery.notifyBar.js"></script>
        <script src="js/unicorn.icheckbox.js"></script>
        
        <script>
        $(function() {
        	 $(".delete_refund").click(function (){
                $index = $(".delete_refund").index(this);
        		 $.ajax({
                     type: "POST",
                     url: "<?php echo site_url('orderRefund/delete') ?>",
                     dataType: 'json',
                     data: {refund_id: $(this).val()},
                     success: function (result) {
                    	 if(result.success){
                            $('.order-refund-list tbody tr').eq($index).fadeOut(500);
                            setTimeout(function(){$('.order-refund-list tbody tr').eq($index).remove();},500);
                    		$.notifyBar({cssClass: "dg-notify-success", html: "删除成功     o(^▽^)o", position: "bottom"});
                    	 }else{
                    		$.notifyBar({cssClass: "dg-notify-error", html: '删除失败,需申请人本人删除', position: "bottom"});
                    	 }
                     }
        		 });
              });

             $('#orderRefund-search').keypress(function(e){
                var keycode = e.charCode;
                    if(keycode == 13)
                    $('#orderRefund-submit').click();
            });
        });    
        </script>
        
        <?php echo $foot ?>

   
    </body>
</html>
