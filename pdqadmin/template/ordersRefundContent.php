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
                    <div class="modal-body">
                        <?= $refund_bills['refund_details'] ?>
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
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="btnclose">Close</button>
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
                            <h1><i class="glyphicon glyphicon-check" aria-hidden="true"></i>ordersRefundContent</h1>
                        </div>	
                        <div class="col-xs-12 col-sm-6 col-lg-6">
                            <?php if ($refund_bills['refund_status'] == 1): ?>
                                <div class="text-right">
                                    <button  class="btn btn-default btn-bgcolor-white" id="cancelRefund"></i> Cancel Refund </button>
                                    <button  class="btn btn-default btn-bgcolor-white" id="shosChangeStatus" data-toggle="modal" data-target="#change_status"></i> Change Status</button>
                                </div>
                            <?php endif; ?>		
                        </div>	
                    </div>

                </div>
                <div id="breadcrumb">
                    <a href="/orderRefund" title="Go to OrderRefund List" class="tip-bottom"><i class="fa fa-tags"></i> ordersRefund </a>
                    <a href="javascript:void(0);" class="current">#<?= $refund_bills['refund_id'] ?> <?= date('Y-m-d H:i:s', $refund_bills['create_time']) ?></a>
                </div>
                <div class="row">	
                    <div class="col-xs-12 col-sm-12 col-lg-12">

                        <div class="widget-box widget-box-hledit widget-box-hledit-ordercontent-left widget-box-hledit-ordercontent-tableleft">
                            <div class="widget-title">
                                <h4 class="pull-left">Order Refund</h4>
                                <div class="clearfix"></div>
                            </div>
                            <div class="widget-content nopadding">
                                <table class="table table-striped ordercontent-table">
                                    <tbody>
                                        <?php foreach ($refund_details as $details): ?>
                                            <?php if ($details['product_id']): ?>  
                                                <tr>
                                                    <td class="image" width="50px">
                                                        <a href="/product/edit/<?= $details['product_id'] ?>" target="_blank"><img class="ordercontent-image" src="<?= $details['image'] ?>" /></a>
                                                    </td>
                                                    <td width="45%">
                                                        <a href="/product/edit/<?= $details['product_id'] ?>" target="_blank" class="ordercontent-table-tda"><?= htmlspecialchars_decode($details['product_name']) ?></a>
                                                        <h6 class="subdued"><?= $details['product_attr'] ?></h6>
                                                        <h6 class="subdued">SKU : <?= $details['product_sku'] ?></h6>
                                                    </td>
                                                    <td width="12%"><?= $this->session->userdata('my_currency') . $details['refund_price'] / 100 ?>×<?= $details['refund_quantity'] ?></td>
                                                    <td><?= $this->session->userdata('my_currency') . $details['refund_amount'] / 100 ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>






                                        <tr>
                                            <td class="image" width="50px"></td>
                                            <td width="45%"></td>
                                            <td width="12%"></td>
                                            <td>
                                                <table class="table--nested table--no-border type--right">
                                                    <tbody>
                                                        <tr class="next-heading">
                                                            <td>refund_quantity</td>
                                                            <td><?= $refund_bills['refund_quantity'] ?></td>
                                                        </tr>

                                                        <tr class="next-heading">
                                                            <td>refund_amount</td>
                                                            <td><?= $this->session->userdata('my_currency') . $refund_bills['refund_amount'] / 100 ?></td>
                                                        </tr>
                                                        <tr class="next-heading">
                                                            <td>Reason for Return</td>
                                                            <td><?=$refund_bills['refund_reason']?></td>
                                                        </tr>
                                                        <tr class="next-heading">
                                                            <td>Preferred Resolution</td>
                                                            <td><?=$refund_bills['refund_resolution']?></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="next-heading">refund_details</td>
                                                            <td class="refund_details"><a href="javascript:void(0);" title="<?= $refund_bills['refund_details'] ?>" data-toggle="modal" data-target="#refund_details"><?= $refund_bills['refund_details'] ?></a></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="next-heading">proposer</td>
                                                            <td><?= $refund_bills['proposer_name'] ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>	
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
        <script>
            $('#btnchangestatus').on('click', function () {
                $("#btnchangestatus").prop('disabled', true);
                $("#btnclose").prop('disabled', true);
                $("#btnchangestatus").text('Saving');
                $.post('<?php echo site_url('orderRefund/paymentRefund') ?>', {
                    refund_id: <?= $refund_bills['refund_id'] ?>,
                    order_number: <?= $refund_bills['order_number'] ?>,
                    pay_pwd: $('#payPwd').val()
                }, function (result) {
                    $('#change_status').modal('hide');
                    if (result.success) {
                        $('#cancelRefund').detach();
                        $('#shosChangeStatus').detach();
                        $.notifyBar({cssClass: "dg-notify-success", html: "退款成功！", position: "bottom"});
                        $("#btnchangestatus").prop('disabled', false);
                        $("#btnclose").prop('disabled', false);
                        $("#btnchangestatus").text('Change Status');
                    } else {
                        $.notifyBar({cssClass: "dg-notify-error", html: result.msg, position: "bottom"});
                        $("#btnchangestatus").prop('disabled', false);
                        $("#btnclose").prop('disabled', false);
                        $("#btnchangestatus").text('Change Status');
                    }

                }, 'json');


            });


            $('#cancelRefund').on('click', function () {
                $.post('<?php echo site_url('orderRefund/cancelRefund') ?>', {
                    refund_id: <?= $refund_bills['refund_id'] ?>
                }, function (result) {
                    if (result.success) {
                        location.reload();
                    } else {
                        $.notifyBar({cssClass: "dg-notify-error", html: '修改失败', position: "bottom"});
                    }

                }, 'json');
            });
        </script>

        <?php echo $foot ?>
    </body>
</html>
