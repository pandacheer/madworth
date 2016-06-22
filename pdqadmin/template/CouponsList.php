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
        <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myLargeModalLabel">Append Coupon user</h4>
                    </div>
                    <div class="modal-body row">
                        <div class="form-group col-lg-12 col-ms-12 col-xs-12">
                            <form id="appendForm">
                                <input type="hidden" id="coupons_id" name="coupons_id"/>
                                <textarea class="form-control" id="member_email" name="member_email" rows="5"></textarea>
                            </form>

                        </div>
                        <div class="row col-lg-12 col-ms-12 col-xs-12 text-right">
                            <button class="btn btn-ms btn-default" data-dismiss="modal">Close</button>
                            <button class="btn btn-ms btn-info" id="appendUser">Save Change</button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="wrapper">
            <?php echo $head; ?>
            <div id="content">
                <div id="content-header" class="mini">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-lg-6">
                            <h1><i class="glyphicon glyphicon-gift" aria-hidden="true"></i>Coupon</h1>
                        </div>	
                        <div class="col-xs-12 col-sm-6 col-lg-6">
                            <div class="pull-right">
                                <a href="<?php echo site_url('coupons/loadAddPage') ?>" class="btn btn-default btn-bgcolor-blue">Add a discount code</a>
                            </div>	
                        </div>	
                    </div>	
                </div>

                <div class="row">

                    <div class="col-xs-12">
                        <ul class="nav nav-tabs ordernav">
                            <li role="presentation" <?php if ($status == 0): ?> class="active"<?php endif; ?>><a href="<?php echo site_url('coupons') ?>">All Discounts</a></li>
                            <li role="presentation" <?php if ($status == 2): ?> class="active"<?php endif; ?>><a href="<?php echo site_url('coupons/index/2') ?>">Active</a></li>
                            <li role="presentation" <?php if ($status == 1): ?> class="active"<?php endif; ?>><a href="<?php echo site_url('coupons/index/1') ?>">Inactive</a></li>
                        </ul>
                        <div class="row">
                            <div class="widget-box widget-box-hledit widget-box-hledit-order-left">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="keyword" value="<?php echo $where;?>" placeholder="Search for...">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default searchbutton" type="button">Go!</button>
                                    </span>
                                </div>
                                <div class="widget-content nopadding">
                                    <table class="table table-striped table-hover with-check Customerslist-table">
                                        <thead>
                                            <tr>
                                                <th class="select"><input type="checkbox" id="title-checkbox" name="title-checkbox" /></th>
                                                <th>Details</th>
                                                <th>Append User</th>
                                                <th>Used</th>
                                                <th>Note</th>
                                                <th>Start/End</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($coupons as $coupon) : ?>
                                                <tr>
                                                    <td class="select"><input type="checkbox" /></td>
                                                    <td>
                                                        <div class="is-discount-disabled">
                                                            <span <?php if ($coupon['status'] == 1): ?> class="mono" <?php endif; ?>><a href="<?php echo site_url('coupons/loadEditPage/' . $coupon['coupons_id']) ?>"><?php echo $coupon['coupons_id'] ?></a></span>
                                                            <span class="badge badge--complete" <?php if ($coupon['status'] == 2): ?>style=" display: none;" <?php endif; ?>>Disabled</span>

                                                            <p <?php if ($coupon['status'] == 1): ?> class="discount-desc" <?php endif; ?>>
                                                                <?php
                                                                if ($coupon['type'] == 3) {
                                                                    echo 'Free Shipping';
                                                                } else {
                                                                    echo ($coupon['type'] == 1) ? $this->session->userdata('my_currency') . number_format($coupon['amount'] / 100, 2) : $coupon['amount'] . '%';
                                                                }
                                                                switch ($coupon['condition']) {
                                                                        case 1:
                                                                            echo ' off all orders';
                                                                            break;
                                                                        case 2:
                                                                            echo ' off orders over '.$this->session->userdata('my_currency') . number_format($coupon['min'] / 100, 2) . ' to ' . number_format($coupon['max'] / 100, 2);
                                                                            break;
                                                                        case 3:
                                                                            echo ' off specific product over '.$this->session->userdata('my_currency') . number_format($coupon['min'] / 100, 2) . ' to ' . number_format($coupon['max'] / 100, 2);
                                                                            break;
                                                                        default:
                                                                            break;
                                                                    }
                                                                ?>
                                                            </p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php if ($coupon['private']==1) : ?>
                                                        <button class="btn btn-default btn-sm" data-toggle="modal" data-target=".bs-example-modal-lg" data-bind="<?php echo $coupon['coupons_id'] ?>">
                                                            <i class="fa fa-user"></i>
                                                        </button>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo $coupon['used'] ?></td>
                                                    <td>
                                                    	<?php echo $coupon['note'] ?>
                                                    </td>
                                                    <td>
                                                        <dl>
                                                            <dt class="subdued">Start:</dt>
                                                            <dd><?php echo date('M j Y', $coupon['start']) ?></dd>

                                                            <dt class="subdued">End:</dt>
                                                            <dd><?php echo $coupon['end'] == 2145888000 ? '---' : date('M j Y', $coupon['end']) ?></dd>
                                                        </dl>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm product-operation" role="group">
                                                            <button type="button" class="btn btn-default product-operation-edit" data-status="<?php echo $coupon['status'] ?>" data-bind="<?php echo $coupon['coupons_id'] ?>"><?php echo ($coupon['status'] == 1) ? 'Enable' : 'Disable' ?></button>
                                                            <button type="button" class="btn btn-default product-operation-detect" data-bind="<?php echo $coupon['coupons_id'] ?>">
                                                                <i class="fa fa-trash-o fa-lg"></i>
                                                            </button>
                                                        </div>
                                                    </td>
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

        <script>
            $('#keyword').keypress(function(e){
                    var keycode = e.charCode;
                        if(keycode == 13)
                        $('.searchbutton').click();
                });
            $(function () {
                var buttonshowhidebtn;
                $(".product-operation-edit").on("click", function () {
                    var that = this;
                    var td = $(that).closest('tr').children('td').eq(1);
                    $.post('<?php echo site_url('coupons/changeStatus') ?>', {
                        coupons_id: $(this).data('bind'),
                        status: $(this).data('status')
                    }, function (result) {
                        if (result.success) {
                            if (result.status === 1) {
                                $(that).text("Enable");
                                $(td).find('span').first().addClass('mono');
                                $(td).find('.badge').show();
                                $(td).find('p').addClass('discount-desc');
                            } else {
                                $(that).text("Disable");
                                $(td).find('span').first().removeClass('mono');
                                $(td).find('.badge').hide();
                                $(td).find('p').removeClass('discount-desc');
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
                    $.post('<?php echo site_url('coupons/del') ?>', {
                        coupons_id: $(that).data('bind')
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

                $('.bs-example-modal-lg').on('show.bs.modal', function (event) {
                    buttonshowhidebtn = $(event.relatedTarget);
                    $("#coupons_id").val(buttonshowhidebtn.attr("data-bind"));
                    $.post("<?php echo site_url('coupons/appendUser') ?>",{existEmail:1,coupons_id:$("#coupons_id").val()},function(data){
                        $('#member_email').html(data);
                    },'html')
                });
                $("#appendUser").on("click", function () {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo site_url('coupons/appendUser') ?>",
                        dataType: 'json',
                        data: $("#appendForm").serialize(),
                        success: function (result) {
                            if (result.success) {
                                alert('优惠券成功分发给' + result.count + '人');
                                $('.bs-example-modal-lg').modal('hide');
                            } else {
                                alert(result.error);
                            }
                        }
                    });
                });
                $('.searchbutton').click(function(){
                    var kw = encodeURI(encodeURI($('#keyword').val()));
                    window.location.href="<?php $a = $this->uri->segment(3)?$this->uri->segment(3):0; echo site_url('coupons/index').'/'.$a; ?>/1/"+kw;
                })
            });
        </script>

    </body>
</html>
