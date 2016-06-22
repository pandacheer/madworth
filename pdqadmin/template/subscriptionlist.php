	<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Customerscontents</title>
        <base href="<?php echo $template ?>">
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
                            <h1><i class="fa fa-users" aria-hidden="true"></i> subscriptionList</h1>
                        </div>
                    </div>	
                </div>

                <div id="breadcrumb">
                    <a href="/customers/index" title="Go to Customers Letter" class="tip-bottom"><i class="fa fa-tags"></i> Customers </a>
                    <span class="current">subscriptionList</span>
                </div>
                <div class="row">

                    <div class="col-xs-12">
                        <div class="row">
                            <div class="widget-box widget-box-hledit widget-box-hledit-order-left">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <?php echo form_open('subscriptionlist/inquire'); ?>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="input" value="<?php echo $where;?>" id = "input-search"placeholder="Search For..." aria-describedby="basic-addon2" list="word">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="submit">Go!</button>
                                            </span>
                                        </div>
                                        <?php echo form_close(); ?>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo form_open('subscriptionlist/datepicker'); ?>
                                        <div class="input-group">
                                            <input type="text" id="datepicker1" name="datepicker1" value="<?php echo $time1; ?>" class="form-control" placeholder="Start Time..." aria-describedby="basic-addon2">
                                            <span class="input-group-addon" id="basic-addon2">To</span>
                                            <input type="text" id="datepicker2" value="<?php echo $time; ?>" name="datepicker2" class="form-control" placeholder="End Time..." aria-describedby="basic-addon2">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="submit">Go!</button>
                                            </span>	
                                        </div>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="widget-content nopadding">
                                            <table class="table table-striped table-hover with-check Customerslist-table">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th class="text-center">Apply Time</th>
                                                        <th>Operation</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php echo $list; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <?php if (!empty($pageArticle)): ?>
                                            <ul class="pagination alternate">
                                                <?php echo $pageArticle; ?>
                                            </ul>
                                        <?php endif; ?>
                                        <span style="float: right;margin: 20px 0;font-size: 20px;font-size: 15px">共<?php echo $count; ?>条记录</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="footer" class="col-xs-12"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div id="footer" class="col-xs-12"></div>
            </div>
        </div>
        <script src="js/jquery.min.js"></script>
        <script src="js/jquery-ui.custom.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.tagsinput.js"></script>
        <script src="js/jquery.icheck.min.js"></script>
        <!--左侧nav-->
        <script src="js/jquery.nicescroll.min.js"></script>
        <script src="js/unicorn.js"></script>
        <script src="js/unicorn.jui.js"></script>
        <?php echo $foot ?>
        <script>
            $(function () {
                $('.iradio_flat-blue').bind('click', function () {
                    /* Act on the event */
                    var checkedStatus = this.checked;
                });
                // Datepicker
                $('#datepicker1').datepicker({
                    changeMonth: true,
                    dateFormat: "yy-mm-dd",
                    changeYear: true,
                    onClose: function (selectedDate) {
                        $("#datepicker2").datepicker("option", "minDate", selectedDate);
                    }
                });
                $('#datepicker2').datepicker({
                    defaultDate: "+1w",
                    changeMonth: true,
                    dateFormat: "yy-mm-dd",
                    changeYear: true,
                    onClose: function (selectedDate) {
                        $("#datepicker1").datepicker("option", "maxDate", selectedDate);
                    }
                });
            });
            $(".delete").click(function () {
                var result = confirm("Please Confirm Your Operation?");
                $num = $(".delete").index(this);
                if (result) {
                    $.post("/subscriptionlist/delete", {
                        id: $(this).val(),
                    }, function (result) {
                        var result = $.parseJSON(result);
                        if (result.success) {
                            $('.Customerslist-table tbody tr').eq($num).fadeOut(160).remove();
                        }else{
                            alert(result.info)
                        }
                    });
                }
            });
            $("#datepicker2").focus(function () {
                $('#datepicker2').attr("value", "");
            });
        </script>
    </body>
</html>
