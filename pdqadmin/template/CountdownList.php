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
        <link rel="stylesheet" href="css/bootstrap-switch.min.css" />
        <!--[if lt IE 9]>
        <script type="text/javascript" src="js/respond.min.js"></script>
        <![endif]-->
        <style>

        </style>
    </head>	
    <body data-color="grey" class="flat"> 
        <div id="wrapper">
            <?php echo $head; ?>

            <div id="content">
                <div id="content-header" class="mini">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-lg-6">
                            <h1><i class="glyphicon glyphicon-time" aria-hidden="true"></i> Countdown</h1>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-lg-6">
                            <a href="<?php echo site_url('countdown/loadAddPage') ?>"><button class="btn btn-info pull-right" type="button"><i class="fa fa-plus fa-sm"></i> New Countdown</button></a>

                        </div>
                    </div>	
                </div>

                <div class="row">

                    <div class="col-xs-12">

                        <div class="row">
                            <div class="widget-box widget-box-hledit widget-box-hledit-order-left">
                                <form method="post" action="<?php echo site_url('countdown/index') ?>">
                                    <div class="input-group">

                                        <input type="text" id="countdown-search" class="form-control" value="<?php if($where!='ALL')echo $where;?>" placeholder="Search for..." name="txtKeyWords">
                                        <span class="input-group-btn">
                                            <button id="countdown-submit" class="btn btn-default" type="submit">Go!</button>
                                        </span>

                                    </div>
                                </form>
                                <ul class="pagination alternate">
                                    <?php if (isset($pages)) echo $pages ?>
                                </ul>
                                <div class="widget-content nopadding">
                                    <table class="table table-striped table-hover table-striped Customerslist-table">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Discont</th>
                                                <th>Start/End</th>
                                                <th>Auto Recount</th>
                                                <th>Rounding</th>
                                                <th>View/Edit</th>
                                                <th>Start/Stop</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody class="countdownlist">
                                            <?php foreach ($countdownList as $countdown) : ?>
                                                <tr>
                                                    <td><?php echo $countdown['name'] ?></td>
                                                    <td><?php echo $countdown['rate'] ? $countdown['rate'] . '%' : number_format($countdown['price'] / 100, 2) ?></td>
                                                    <td><?php echo date('Y-m-d H:i', $countdown['start']) ?><br /><?php echo date('Y-m-d H:i', $countdown['end']) ?></td>
                                                    <td><?php echo $countdown['auto_recount'] == 1 ? 'N' : 'Y' ?></td>
                                                    <td><?php echo $countdown['decimal'] > -0.1 ? $countdown['decimal'] / 100 : '---' ?></td>
                                                    <td><a href="<?php echo site_url('countdown/loadEditPage/' . $countdown['id']) ?>"><button type="button" class="btn btn-default btn-sm product-operation-detect"  data-bind="<?php echo $countdown['id'] ?>"  <?php if ($countdown['status'] == 2) echo 'style="display: none;"'; ?> ><i class="fa fa-pencil fa-lg"></i></button></a></td>
                                                    <td><input type="checkbox" class="switchbox" <?php if ($countdown['status'] == 2) echo "checked"; ?>  data-animate="false" data-bind="<?php echo $countdown['id'] ?>" data-size="small" data-on-color="success" data-off-color="danger"></td>
                                                    <td><button type="button" class="btn btn-default btn-sm product-operation-detect" data-bind="<?php echo $countdown['id'] ?>" id="delete"><i class="fa fa-trash-o fa-lg"></i></button></td>
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
        <script src="js/unicorn.jui.js"></script>
        <script src="js/bootstrap-switch.js"></script>
        <?php echo $foot ?>

        <script >

            $('#countdown-search').keypress(function(e){
                var keycode = e.charCode;
                    if(keycode == 13)
                    $('#countdown-submit').click();
            });
            $(document).ready(function () {
                $('.fa-trash-o').parents('button').click(function () {
                    var r = confirm("Are you sure you want to delete ?")
                    if (r === true) {
                        $that = this;
                        $.post('<?php echo site_url('countdown/delete') ?>', {
                            countdown_id: $($that).data('bind')
                        }, function (result) {
                            if (result.success) {
                                $($that).parent().parent().detach();
                                return false;
                            } else {
                                alert(result.error);
                            }
                        }, 'json');
                    } else {
                        return false;
                    }
                });

                $(".switchbox").bootstrapSwitch();

                $('.countdownlist input:checked').parents('tr').children('td').children('button').hide(1);

                $('.switchbox').on('switchChange.bootstrapSwitch', function () {
                    $(this).bootstrapSwitch('toggleDisabled');
                    $newStatus = $(this).bootstrapSwitch('state');
                    $that = this;
                    $.post('<?php echo site_url('countdown/changeStatus') ?>', {
                        countdown_id: $($that).data('bind'),
                        status: $newStatus
                    }, function (result) {
                        if (result.success) {
                            $($that).bootstrapSwitch('toggleDisabled');
                            if ($newStatus) {
                                $($that).parents('tr').children('td').find('button').fadeOut(100);
                            } else {
                                $($that).parents('tr').children('td').find('button').fadeIn(100);
                            }
                        } else {
                            $($that).bootstrapSwitch('toggleDisabled');
                            $($that).bootstrapSwitch('toggleState', true);
                        }

                    }, 'json');
                });




            });
        </script>
    </body>
</html>
