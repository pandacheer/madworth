<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo $template ?>">
        <title>Discounts</title>
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
                            <h1><i class="fa fa-users" aria-hidden="true"></i>orderApplyList</h1>
                        </div>
                    </div>	
                </div>
                <div id="breadcrumb">
                    <a href="/orderRefundApply" title="Go to order List" ><i class="fa fa-tags"></i>orderApply</a>
                </div>
                <div class="row">
                    <div class="row">
                     <form method="post" action="<?php echo site_url('orderRefundApply/index') ?>">
                        <div class="col-md-12">
                         
                                <div class="col-md-3">
                                    <select name="s_creator" class="form-control">
                                       <option value="0">请选择创建人</option>
                                       <?php foreach ($userName as $user): ?>
                                         <option value="<?=$user['user_account'] ?>"  <?php if ($whereCreator == $user['user_account']) echo 'selected="selected"' ?> ><?=$user['user_account'] ?></option>   
                                       <?php endforeach; ?> 
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="s_reason" class="form-control">
                                       <option value="0">请选择退货原因</option>
                                       <option value="Missing item" <?php if ($whereReason == 'Missing item') echo 'selected="selected"' ?>>Missing item</option>
                                       <option value="Wrong item"   <?php if ($whereReason == 'Wrong item') echo 'selected="selected"' ?>>Wrong item</option>
                                       <option value="Wrong size"   <?php if ($whereReason == 'Wrong size') echo 'selected="selected"' ?>>Wrong size</option>
                                       <option value="Quality issues" <?php if ($whereReason == 'Quality issues') echo 'selected="selected"' ?>>Quality issues</option>
                                       <option value="Damaged in transit" <?php if ($whereReason == 'Damaged in transit') echo 'selected="selected"' ?>>Damaged in transit</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group" style="padding:0 0 10px 0">
                                        <input type="text" id="orderApplyList-search" class="form-control" name="txtKeyWords" value="<?php if($where!='ALL')echo $where;?>" placeholder="Search order number...">
                                        <span class="input-group-btn">
                                            <button id="orderApplyList-submit" class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Search</button>
                                        </span>
                                    </div>
                                </div>
                           
                         </div>
                      </form>  
                    </div>
                    <div class="col-xs-12">

                        <div class="row">
                            <div class="widget-box widget-box-hledit widget-box-hledit-order-left">
                                <div class="widget-content nopadding">
                                    <table class="table table-striped table-hover Customerslist-table">
                                        <thead>
                                            <tr>
                                                <th>订单号</th>
                                                <th>时间</th>
                                                <th>产品名称</th>
                                                <th>状态</th>
                                                <th>退货原因</th>
                                                <th>创建人</th>
                                                <th>查看详情</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        
                                           <?php foreach ($refundApply as $refund): ?>
                                           <?php if($refund['refund_proName']):?>
	                                            <tr>
	                                                <td>
	                                                	<a href="/ordersContent/<?=$refund['order_number']?>"><?=$refund['order_number']?></a>
	                                                	<?php if($refund['equal_order']==2):?>
	                                                		<i class="glyphicon glyphicon-info-sign"></i>
	                                                	<?php endif; ?>
	                                                </td>
	                                                <td><a href="/orderRefundApply/applyContent/<?=$refund['_id']?>"><?=date('Y-m-d', $refund['create_time'])?></a></td>
	                                                <td><a href="/product/edit/<?=$refund['refund_proId']?>"><?=$refund['refund_proName']?></a></td>
	                                                <td>
	                                                  <?php if($refund['status']==1):?> 未处理
	                                                  <?php else:?>
	                                                                                                                     已处理
	                                                  <?php endif; ?>
	                                                </td>
	                                                <td><?=$refund['refund_reason']?></td>
	                                                <td><?=$refund['creator']?></td>
	                                                <td>
	                                                   <a href="/orderRefundApply/applyContent/<?=$refund['_id']?>">
	                                                    <button class="btn btn-default btn-sm">
	                                                        <i class="fa fa-eye"></i>
	                                                    </button>
	                                                   </a>
	                                                </td>
	                                            </tr>
	                                        <?php else:?>
	                                        	<tr style="color:red;">
	                                                <td>
	                                                	<a style="color:red;" href="/ordersContent/<?=$refund['order_number']?>"><?=$refund['order_number']?></a>
	                                                	<?php if($refund['equal_order']==2):?>
	                                                		<i class="glyphicon glyphicon-info-sign"></i>
	                                                	<?php endif; ?>
	                                                </td>
	                                                <td><a style="color:red;" href="/orderRefundApply/applyContent/<?=$refund['_id']?>"><?=date('Y-m-d', $refund['create_time'])?></a></td>
	                                                <td>此信息为丢包投诉</td>
	                                                <td>
	                                                  <?php if($refund['status']==1):?> 未处理
	                                                  <?php else:?>
	                                                                                                                     已处理
	                                                  <?php endif; ?>
	                                                </td>
	                                                <td>/</td>
	                                                <td>/</td>
	                                                <td>
	                                                   <a href="/orderRefundApply/applyContent/<?=$refund['_id']?>">
	                                                    <button class="btn btn-default btn-sm">
	                                                        <i class="fa fa-eye"></i>
	                                                    </button>
	                                                   </a>
	                                                </td>
	                                            </tr>
	                                        	
	                                        <?php endif?>
                                            <?php endforeach; ?> 
                           
                                        </tbody>
                                    </table>
                                    <ul class="pagination alternate">
                                		<?php if (isset($pages)) echo $pages ?>
                        		    </ul>		
                                </div>
                            </div>	
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
        <script src="js/unicorn.icheckbox.js"></script>
        <script type="text/javascript">

        $('#orderApplyList-search').keypress(function(e){
            var keycode = e.charCode;
                if(keycode == 13){
                    $('#orderApplyList-submit').click();
                }
        });

        </script>

        <?php echo $foot ?>
    </body>
</html>
