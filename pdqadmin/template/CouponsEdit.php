<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo $template ?>">
        <title>Discountscontent</title>
        <meta charset="UTF-8" />
        <!--<meta name="viewport" content="width=device-width, initial-scale=1.0" />-->
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
                            <h1><i class="glyphicon glyphicon-gift" aria-hidden="true"></i>Edit a Coupon</h1>
                        </div>	
                        <div class="col-xs-12 col-sm-6 col-lg-6">
                            <div class="text-right">
                                <a href="javascript:void(0)" onClick="javascript:history.back(-1);"><button type="button" class="btn btn-default btn-bgcolor-white">Cancel</button></a>
                            </div>	
                        </div>	
                    </div>

                </div>
                <div id="breadcrumb">
                    <a href="/coupons" title="Go to Coupon List" class="tip-bottom"><i class="fa fa-tags"></i>Coupon</a>
                    <a class="current">Edit a Coupon</a>
                </div>
                <div class="row">
                    <div class="discountscontent-box">
                        <div class="col-xs-12 col-sm-3 col-lg-3">
                            <div class="discountscontent-box-left">
                                <h4>Coupon details</h4>
                                <h6 class="subdued discountscontent-box-left-Create">Create your Coupon code, and specify the usage limit.</h6>
                            </div>
                        </div>	
                        <div class="col-xs-12 col-sm-9 col-lg-9">
                            <div class="discountscontent-box-right">
                                <h5><b>Coupon code</b></h5>
                                <input type="text" class="form-control" name="coupons_id" readonly="readonly" value="<?php echo $coupons['coupons_id'] ?>">
                                <h5><b>How many times can this Coupon be used?</b></h5>
                                <h5><input type="text" <?php if ($coupons['frequency'] == 0) echo 'disabled="disabled"' ?> class="group-radius-left" name="frequency" value="<?php if ($coupons['frequency']) echo $coupons['frequency'] ?>"><span class="group-radius-right"><input type="checkbox" name="frequencyLimit" value="1" <?php if ($coupons['frequency'] == 0) echo 'checked="checked"' ?>>No limit</span></h5>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="discountscontent-box discountscontent-box-discounttype">
                        <div class="col-xs-12 col-sm-3 col-lg-3">
                            <div class="discountscontent-box-left">
                                <h4>Coupon type</h4>
                                <h6 class="subdued discountscontent-box-left-Create">Select the type of Coupon, and set any extra conditions.</h6>
                            </div>
                        </div>	
                        <div class="col-xs-12 col-sm-9 col-lg-9">
                            <div class="discountscontent-box-right">
                                <select class="form-control" name="type" id="discount">
                                    <option value="1" <?php echo ($coupons['type'] == 1) ? 'selected="selected"' : ''; ?>>$ AUD</option>
                                    <option value="2" <?php echo ($coupons['type'] == 2) ? 'selected="selected"' : ''; ?>>% Discount</option>
                                    <option value="3" <?php echo ($coupons['type'] == 3) ? 'selected="selected"' : ''; ?>>Free Shipping</option>
                                </select>
                                <h5 class="discountscontent-box-discounttype-h5">
                                    <small>Take</small><input type="text" class="group-radius-left" name="amount" <?php echo $coupons['amount'] ? 'value="' . $coupons['amount'] . '"' : 'disabled="disabled"' ?>><span class="group-radius-right"><?php echo ($coupons['type'] == 1) ? '$' : '%'; ?></span><small>off for</small>
                                </h5>
                                <select class="form-control" id="condition" name="condition">
                                    <option value="1" <?php echo ($coupons['condition'] == 1) ? 'selected="selected"' : ''; ?>>all orders</option>
                                    <option value="2" <?php echo ($coupons['condition'] == 2) ? 'selected="selected"' : ''; ?>>orders over</option>
                                    <option value="3" <?php echo ($coupons['condition'] == 3) ? 'selected="selected"' : ''; ?>>specific product</option>
                                    <!--                                        <option>collection</option>
                                                                            <option>specific product</option>
                                                                            <option>customer in group</option>-->
                                </select>

                                <div class="form-group col-lg-5" style="display:none">
                                    <input type="text" class="form-control tags-rightinput" placeholder="Vintage, cotton, summer">
                                </div>

                                <div class="form-group col-lg-5" <?php echo ($coupons['condition'] == 1) ? 'style="display:none"' : ''; ?> >
                                    <div class="input-group" style="padding-top:5px">
                                        <input type="text" class="form-control"  placeholder="Min" name="min" value="<?php echo $coupons['min'] / 100 ?>">
                                        <div class="input-group-addon">to</div>
                                        <input type="text" class="form-control"  placeholder="Max" name="max"  value="<?php echo $coupons['max'] / 100 ?>">
                                    </div>              
                                </div> 

                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="discountscontent-box discountscontent-box-daterange">
                        <div class="col-xs-12 col-sm-3 col-lg-3">
                            <div class="discountscontent-box-left">
                                <h4>Date range</h4>
                                <h6 class="subdued discountscontent-box-left-Create">Specify when this Coupon begins and ends.</h6>
                            </div>
                        </div>	
                        <div class="col-xs-12 col-sm-9 col-lg-9">
                            <div class="discountscontent-box-right">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-3 col-lg-3">
                                        <b>Coupon begins:</b> 
                                        <h5>
                                            <input id="ui-datepicker" type="text" class="form-control input-sm" name="start" value="<?php echo date('m/d/Y', $coupons['start']) ?>" />
                                        </h5>
                                    </div>
                                    <div class="col-xs-12 col-sm-8 col-lg-8">
                                        <b>Coupon expires (end of day):</b> 
                                        <h5>
                                            <input id="ui-datepicker-2" type="text" class="group-radius-left" name="end" <?php echo $coupons['end'] == 2145888000 ? 'disabled' : 'value="' . date('m/d/Y', $coupons['end']) . '"' ?> />
                                            <span class="group-radius-right ui-datepicker-2span"><input type="checkbox" name="neverExpires" value="1" <?php echo $coupons['end'] == 2145888000 ? 'checked' : '' ?>>Never expires</span>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="discountscontent-box discountscontent-box-daterange">
                        <div class="col-xs-12 col-sm-3 col-lg-3">
                            <div class="discountscontent-box-left">
                                <h4>Note</h4>
                                <h6 class="subdued discountscontent-box-left-Create">Specify when this Coupon begins and ends.</h6>
                            </div>
                        </div>  
                        <div class="col-xs-12 col-sm-9 col-lg-9">
                            <div class="discountscontent-box-right">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <textarea  class="form-control"><?php echo $coupons['note'] ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="discountscontent-box discountscontent-box-daterange">
                        <div class="col-xs-12 col-sm-3 col-lg-3">
                            <div class="discountscontent-box-left">
                                <h4>User Group</h4>
                                <h6 class="subdued discountscontent-box-left-Create">Choose the user group.</h6>
                            </div>
                        </div>  
                        <div class="col-xs-12 col-sm-9 col-lg-9">
                            <div class="discountscontent-box-right">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-3 col-lg-3 discount All_user">  

                                        <input type="radio" class="form-control" value="2" name="private" <?php echo $coupons['private'] == 2 ? 'checked' : '' ?> />
                                        <span>All user</span>
                                    </div>
                                    <div class="col-xs-12 col-sm-8 col-lg-8 discount Specific_User">

                                        <input type="radio" class="form-control" value="1" name="private" <?php echo $coupons['private'] == 1 ? 'checked' : '' ?> />
                                        <span>Specific User</span>
                                        <!--<h5><input type="text" style="display:none" /></h5>-->    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="discountscontent-box discountscontent-box-daterange">
                        <div class="col-xs-12 col-sm-3 col-lg-3">
                            <div class="discountscontent-box-left">
                                <h4>前台页面是否显示</h4>
                                <h6 class="subdued discountscontent-box-left-Create">个人中心不会显示这个优惠券存在，客户只有知道这个优惠券存在才可用。f</h6>
                            </div>
                        </div>  
                        <div class="col-xs-12 col-sm-9 col-lg-9">
                            <div class="discountscontent-box-right">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-3 col-lg-3 discount All_user">  

                                        <input type="radio" class="form-control" value="1" name="display" <?php echo $coupons['display'] == 1 ? 'checked' : '' ?> />
                                        <span>所有用户可见</span>
                                    </div>
                                    <div class="col-xs-12 col-sm-8 col-lg-8 discount Specific_User">

                                        <input type="radio" class="form-control" value="2" name="display" <?php echo $coupons['display'] == 2 ? 'checked' : '' ?> />
                                        <span>部分用户可见</span>
                                        <!--<h5><input type="text" style="display:none" name="emailList" /></h5>-->    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-xs-12">
                        <div class="text-right">
                            <a href="javascript:void(0)" onClick="javascript:history.back(-1);"><button type="button" class="btn btn-default btn-bgcolor-white">Cancel</button></a>
                        </div>	
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
                                    var checkboxClass = 'icheckbox_flat-blue';
                                    var radioClass = 'iradio_flat-blue';
                                    $("input[name='private']").iCheck({
                                        checkboxClass: checkboxClass,
                                        radioClass: radioClass
                                    });
                                    $("input[name='display']").iCheck({
                                        checkboxClass: checkboxClass,
                                        radioClass: radioClass
                                    });
                                    $('.group-radius-right input').change(function () {
                                        var index = $('.group-radius-right input').index(this);
                                        if (index == 1) {
                                            index += 1;
                                        }
                                        if ($(this).is(':checked')) {
                                            $(".group-radius-left").eq(index).attr("disabled", true);
                                        } else {
                                            $(".group-radius-left").eq(index).attr("disabled", false);
                                        }
                                    });


                                    $("#condition").change(function () {
                                        $val = $(this).val();
                                        switch ($val) {
                                            case "1" :
                                                $("#condition + div + div").fadeOut(100);
                                                $("#condition + div").fadeOut(100);
                                                break;
                                            case "2" :
                                                $("#condition + div + div").fadeIn(100);
                                                $("#condition + div").fadeOut(1);
                                                break;
                                            case "3" :
                                                $("#condition + div + div").fadeIn(100);
                                                $("#condition + div").fadeOut(1);
                                                break;
                                            case "4":
                                                $("#condition + div").fadeIn(100);
                                                $("#condition + div + div").fadeOut(1);
                                                break;
                                        }

                                    });
                                    $("#discount").change(function () {
                                        $val = $(this).val();
                                        switch ($val) {
                                            case "1" :
                                                $("#discount + h5").children('small:first-child,input,span').fadeIn(100);
                                                $("#discount + h5").children('input').attr('disabled', false);
                                                $("#discount + h5 span").text('$');
                                                //$("#discounttype + div").fadeOut(100);
                                                break;
                                            case "2" :
                                                $("#discount + h5").children('small:first-child,input,span').fadeIn(100);
                                                $("#discount + h5").children('input').attr('disabled', false);
                                                $("#discount + h5 span").text('%');
                                                break;
                                            case "3":
                                                $("#discount + h5").children('input').attr('disabled', true);
                                                break;
                                        }
                                        ;
                                    });
                                });
        </script>
    </body>
</html>
