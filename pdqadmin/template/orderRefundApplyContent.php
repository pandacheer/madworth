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
        <div class="modal fade" id="refund_details">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Refund reason</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->




        <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    ...
                </div>
            </div>
        </div>


        <div class="modal fade bs-example-modal-sm" id="change_status">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Change Status</h4>
                    </div>
                    <div class="modal-body">
                        <h4>Password:</h4>
                        <input type="password" id="payPwd">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn pull-right btnCart" id="btnchangestatus" data-image=""  >Change Status</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div id="wrapper">
            <?php echo $head ?>

            <div id="content">
                <div id="content-header" class="mini">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-lg-6">
                            <h1><i class="glyphicon glyphicon-check" aria-hidden="true"></i>orderRefundApplyContent</h1>
                        </div>	
                    </div>
                </div>
                <div id="breadcrumb">
                    <a href="/orderRefundApply" title="Go to OrderRefund List" ><i class="fa fa-tags"></i> orderRefundApply </a>
                </div>
                <div class="row">	
                    <div class="col-xs-12 col-sm-12 col-lg-12">

                        <div class="widget-box widget-box-hledit widget-box-hledit-ordercontent-left widget-box-hledit-ordercontent-tableleft">
                            <div class="widget-title">
                                <h4 class="pull-left">orderApply</h4>
                                <div class="clearfix"></div>
                            </div>
                            <div class="widget-content nopadding">
                            
                             <?php if($refundApplyDetails['refund_proName']):?>
                                <div class="row">
                                    <div class="col-xs-1">
                                        <a href="/product/edit/<?= $details['product_id'] ?>" target="_blank"><img class="ordercontent-image" src="<?= $details['image'] ?>" /></a>
                                    </div>
                                    <div class="col-xs-5">
                                        <a href="/product/edit/<?= $details['product_id'] ?>" target="_blank" class="ordercontent-table-tda"><?= htmlspecialchars_decode($details['product_name']) ?></a>
                                        <h6 class="subdued"><?= $details['product_attr'] ?></h6>
                                        <h6 class="subdued">SKU : <?= $details['product_sku'] ?></h6>
                                    </div>
                                    <div class="col-xs-6">
                                        <div>
                                         <?php foreach ($refundApplyDetails['pics'] as $pics): ?>
                                            <img class="ordercontent-image" src="<?=$IMAGE_DOMAIN.$pics['img'] ?>" />
                                         <?php endforeach; ?>         
                                        </div>
                                    </div>
                                </div>
                                
                                
                              <?php endif?>  
                                
                                <div class="row">
                                    <div class="col-xs-6"></div>
                                    <div class="col-xs-6">
                                        <table class="table--nested table--no-border type--right">
                                         <?php if($refundApplyDetails['refund_proName']):?>
                                            <tbody>
                                                <tr class="next-heading">
                                                    <td>Reason for Return</td>
                                                    <td><?=$refundApplyDetails['refund_reason']?></td>
                                                </tr>
                                                <tr>
                                                    <td class="next-heading">refund_details</td>
                                                    <td class="refund_details"><?= $refundApplyDetails['refund_details'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="next-heading">creator</td>
                                                    <td><?= $refundApplyDetails['creator'] ?></td>
                                                </tr>
                                            </tbody>
                                         <?php else:?>
                                            <tbody>
                                                <tr>
                                                    <td class="next-heading">refund_details</td>
                                                    <td class="refund_details"><?= $refundApplyDetails['refund_details'] ?></td>
                                                </tr>
                                            </tbody>  
                                         <?php endif?>  
                                        </table>
                                    </div>
                                </div>
                                
                                <?php if ($refundApplyDetails['status']==1) : ?>
	                                <?php echo form_open('orderRefundApply/up_status') ?>
	                                    <input type="hidden" value="<?= $refundApplyDetails['_id'] ?>"  name="d_id"/>
	                                	<div class="row"><div class="col-xs-12"><button class="btn btn-default btn-bgcolor-white pull-right">审核</button></div></div>
	                                <?php echo form_close(); ?>
	                            <?php else: ?>
		                     		    <div class="type--right">operator: <?= $refundApplyDetails['operator'] ?></div>
		                		<?php endif; ?>
		                		
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
        <script src="js/jquery.notifyBar.js"></script>
        <!--左侧nav-->
        <script src="js/jquery.nicescroll.min.js"></script>
        <script src="js/unicorn.js"></script>


        <?php echo $foot ?>
        <script>
            $(function(){
                $(".ordercontent-image").click(function(){
                    var width = $(this).width();
                    if(width==80)
                    {
                        $(this).width(150);
                    }
                    else
                    {
                        $(this).width(80);
                    }
                });
            });
        </script>
    </body>
</html>
