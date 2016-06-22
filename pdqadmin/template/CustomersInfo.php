<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo $template ?>">
        <title>Customerscontents</title>
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
                        <div class="col-xs-12">
                            <h1><i class="fa fa-users" aria-hidden="true"></i>customer</h1>
                        </div>	
                    </div>

                </div>
                <div id="breadcrumb">
                    <a href="/customers/index" title="Go to Customers List" class="tip-bottom"><i class="fa fa-tags"></i> Customers </a>
                    <span class="current"><?php echo $member['member_name'] ?></span>
                </div>
                <div class="row">	
                    <div class="col-xs-12 col-sm-8 col-lg-8">

                        <div class="widget-box widget-box-hledit widget-box-hledit-ordercontent-left widget-box-hledit-customerscontent-tableleft">
                            <div class="widget-title">
                                <h4>Orders</h4>
                            </div>
                            <div class="widget-content nopadding">
                                <table class="table orderlist-table customerscontent-table">
                                    <thead>
                                        <tr>
                                            <th>Order</th>
                                            <th>Date</th>
                                            <th>Payment</th>
                                            <th>Fulfillment</th>
                                            <th class="tr">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orderList as $orderInfo) : ?>
                                            <!--order_id,order_number,payment_amount,create_time,send_status,pay_status,doc_status-->
                                            <tr class="orderlist-table-special">
                                                <td><a href="/ordersContent/<?php echo $orderInfo['order_number'] ?>" class="customerscontent-table-tda">#<?php echo $orderInfo['order_number'] ?></a><div class="x-small block"><?php echo $sysDocStatus[$orderInfo['doc_status']] ?></div></td>
                                                <td><span><?php echo date('Y-m-d H:i', $orderInfo['create_time']) ?></span></td>
                                                <td class="status">
                                                    <span class="badge badge--complete">
                                                        <?php echo $sysPayStatus[$orderInfo['pay_status']] ?>
                                                    </span>
                                                </td>
                                                <td class="status">
                                                    <span class="badge badge--attention">

                                                        <?php echo $sysSendStatus[$orderInfo['send_status']] ?>
                                                    </span>
                                                </td>
                                                <td class="total tr"><span>$<?php echo number_format($orderInfo['payment_amount'] / 100, 2) ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        
                                    </tbody>
                                </table>
                                <ul class="pagination alternate" style="margin-left: 20px;">
                                    <?php if (isset($pages)) echo $pages ?>
                                </ul>		
                            </div>
                        </div>	

                        <div class="widget-box widget-box-hledit widget-box-hledit-ordercontent-left">
                            <div class="widget-title">
                                <h4>Additional Details</h4>
                            </div>
                            <div class="widget-content nopadding">
                                <form>
                                    <div class="form-group">
                                        <label for="orderDetails">Note</label>
                                        <input type="text" class="form-control" id="orderDetails" placeholder="Add a note to this order…">
                                    </div>
                                    <div class="form-group">
                                        <label for="orderTags">Tags</label>
                                        <input type="text" class="form-control" id="orderTags" placeholder="Reviewed, packed, delivered">
                                    </div>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-default" disabled="disabled">save</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                        </div>	
                    </div>
                    <div class="col-xs-12 col-sm-4 col-lg-4">
                        <div class="widget-box widget-box-hledit widget-box-hledit-ordercontent-right">
                            <h4 class="customersconten-right-Overview-box"><span>Overview</span><a href="#">Edit</a></h4>
                            <div class="customerscontent-right-box customerscontent-right-Overview">
                                <h6><i class="fa fa-envelope-o fa-fw"></i>
                                    <a class="inline-block" href="mailto:<?php echo $member['member_email'] ?>"><?php echo $member['member_email'] ?></a>
                                </h6>
                                <h6><i class="fa fa-bell fa-fw"></i><?php echo date('Y-m-d H:i', $member['login_time']) ?></h6>
                                <h6><i class="fa fa-user fa-fw"></i><?php echo $member['member_name'] ?></h6>
                                <h6><i class="fa fa-beer fa-fw"></i><span>$<?php echo number_format($member['order_spent']/100, 2) ?></span> from completed orders</h6>
                            </div>
                        </div>	
                        <div class="widget-box widget-box-hledit widget-box-hledit-ordercontent-right">
                            <h4 class="customersconten-right-address"><span>Address</span></h4>
                            <?php foreach ($memberReceives as $memberReceive) : ?>
                                <div class="customerscontent-right-box customerscontent-right-box-address">
                                    <i class="fa fa-user fa-fw  user-istyle"></i>
                                    <h6><?php echo $memberReceive['receive_lastName'] ?></h6>
                                    <h6><?php echo $memberReceive['receive_firstName'] ?></h6>
                                    <h6><?php echo $memberReceive['receive_company'] ?></h6>
                                    <h6><?php echo $memberReceive['receive_add1'] ?></h6>
                                    <h6><?php echo $memberReceive['receive_add2'] ?></h6>
                                    <h6><?php echo $memberReceive['receive_province'] ?>,<?php echo $memberReceive['receive_city'] ?></h6>
                                    <h6><?php echo $this->session->userdata('my_countryName') ?><i class="fa fa-envelope-o fa-fw"></i></h6>
                                    <h6><i class="fa fa-envelope-o fa-fw"></i><?php echo $memberReceive['receive_zipcode'] ?></h6>
                                </div>
                                <div class="customerscontent-right-box-show-address"></div>
                            <?php endforeach; ?>

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
        <script src="js/jquery.icheck.min.js"></script>
        <script src="js/bootstrap.min.js"></script>

        <script src="js/jquery.nicescroll.min.js"></script>
        <script src="js/unicorn.js"></script>
        <script src="js/unicorn.icheckbox.js"></script>
        <?php echo $foot ?>

        <script>
            $('#myTab a').click(function (e) {
                e.preventDefault()
                $(this).tab('show')
            })
        </script>
    </body>
</html>
