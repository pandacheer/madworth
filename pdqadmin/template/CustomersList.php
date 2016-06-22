<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo $template ?>">
        <title>Customers</title>
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
                            <h1><i class="fa fa-users" aria-hidden="true"></i>customerAnalysis</h1>
                        </div>	
                        <div class="col-xs-12 col-sm-6 col-lg-6">

                        </div>	
                    </div>	
                </div>

                <div class="row">

                    <div class="col-xs-12">

                        <div class="row">
                            <div class="widget-box widget-box-hledit widget-box-hledit-order-left">
                                <form method="post" action="<?php echo site_url('customers/index') ?>">
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="<?php if($where!='ALL')echo $where;?>" placeholder="Search for..." name="txtKeyWords" value="<?php echo ($txtKeyWords == '' or $txtKeyWords == 'ALL') ? '' : $txtKeyWords ?>">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit">Go!</button>
                                        </span>
                                    </div>
                                </form>
                                <ul class="pagination alternate">
                                    <?php if (isset($pages)) echo $pages ?>
                                </ul>
                                <div class="widget-content nopadding">
                                    <table class="table table-striped table-hover with-check Customerslist-table">
                                        <thead>
                                            <tr>
                                                <th class="select"><input type="checkbox" id="title-checkbox" name="title-checkbox" /></th>
                                                <th>Email</th>
                                                <th class="text-center">Orders</th>
                                                <th class="text-center">Last Order</th>
                                                <th>Total Spent</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($members as $member) : ?>
                                                <tr>
                                                    <td class="select"><input type="checkbox" /></td>
                                                    <td><a href="<?php echo site_url('customers/getInfo/' . $member['member_id'] . '/' . $member['order_spent']) ?>"><?php echo $member['member_email'] ?></a></td>
                                                    <td class="text-center"><?php echo $member['member_orders'] ?></td>
                                                    <td class="text-center"><?php echo $member['member_orders']?date('Y-m-d',$member['last_order']):'-' ?></td>
                                                    <td><?php echo number_format($member['order_spent']/100, 2) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>	
                                </div>
                                <ul class="pagination alternate">
                                    <?php if (isset($pages)) echo $pages ?>
                                </ul>
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
        <script src="js/unicorn.icheckbox.js"></script>
        <?php echo $foot ?>

    </body>
</html>
