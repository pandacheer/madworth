<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo $template ?>">
        <title>Discount Collection</title>
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
                            <h1><i class="fa fa-users" aria-hidden="true"></i>DiscountList</h1>
                        </div>  
                        <div class="col-xs-12 col-sm-6 col-lg-6">
                            <div class="pull-right">
                                <a href="<?php echo site_url('discount/loadAddPage') ?>" class="btn btn-default btn-bgcolor-blue">Add a discount collection</a>
                            </div>
                        </div>  
                    </div>  
                </div>

                <div id="breadcrumb">
                    <a href="/customers/index" class="tip-bottom"><i class="fa fa-tags"></i> Discount </a>
                    <span class="current">DiscountList</span>
                </div>

                <div class="row">
                    <div class="col-xs-12">               
                        <div class="input-group">
                            <input type="text" class="form-control" name="input" value="" id = "input-search"placeholder="Search For..." aria-describedby="basic-addon2" >
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">Go!</button>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-xs-12">

                        <div class="row">
                            <div class="widget-box widget-box-hledit widget-box-hledit-order-left">
                                <div class="widget-content nopadding">
                                    <table class="table table-striped table-hover with-check Customerslist-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 30%">Collection</th>
                                                <th class="text-center" style="width: 30%">Describe</th>
                                                <th class="text-center" style="width: 20%">Discount Type</th>
                                                <th style="width: 20%">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($discounts as $discount) : ?>
                                                <tr>
                                                    <td><a href="/discount/loadEditPage/<?php echo $discount['collection_id'] ?>"><?php echo $discount['collection_title'] ?></a></td>
                                                    <td><?php echo $discount['title'] ?></td>
                                                    <td class="text-center"><?php echo $discount['type'] == 1 ? 'Cash' : 'Percentage' ?></td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm product-operation" role="group">
                                                            <button type="button" class="btn btn-default product-operation-edit" data-status="<?php echo $discount['status'] ?>" data-bind="<?php echo $discount['collection_id'] ?>"><?php echo $discount['status'] == 1 ? 'Enable' : 'Disable' ?></button>
                                                            <button type="button" class="btn btn-default product-operation-detect" data-bind="<?php echo $discount['collection_id'] ?>">
                                                                <i class="fa fa-trash-o fa-lg"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>    
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
        <script src="js/unicorn.icheckbox.js"></script>
        <?php echo $foot ?>
        <script>
            $('#keyword').keypress(function (e) {
                var keycode = e.charCode;
                if (keycode == 13)
                    $('.searchbutton').click();
            });
            $(function () {
                var buttonshowhidebtn;
                $(".product-operation-edit").on("click", function () {
                    var that = this;
                    var td = $(that).closest('tr').children('td').eq(1);
                    $.post('<?php echo site_url('discount/changeStatus') ?>', {
                        collection_id: $(this).data('bind'),
                        status: $(this).data('status')
                    }, function (result) {
                        if (result.success) {
                            if (result.status === 1) {
                                $(that).text("Enable");
                            } else {
                                $(that).text("Disable");
                            }
                            $(that).data('status', result.status);

                        } else {
                            alert(result.error);
                        }
                    }, 'json');
                });
                $(".product-operation-detect").on("click", function () {
                    var r = confirm("Are you sure you want to delete ?");
                    if (r == true) {
                        var that = this;
                        $.post('<?php echo site_url('discount/del') ?>', {
                            collection_id: $(that).data('bind')
                        }, function (result) {
                            if (result.success) {
                                $(that).closest('tr').detach();
                            } else {
                                alert(result.error);
                            }
                        }, 'json');
                    } else {
                        return false;
                    }
                });

                $('.searchbutton').click(function () {
                    var kw = encodeURI(encodeURI($('#keyword').val()));
                    window.location.href = "<?php $a = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
        echo site_url('coupons/index') . '/' . $a; ?>/1/" + kw;
                });
            });
        </script>
    </body>
</html>
