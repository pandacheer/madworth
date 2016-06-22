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
                            <h1><i class="glyphicon glyphicon-gift" aria-hidden="true"></i>Add a Coupon</h1>
                        </div>	
                        <div class="col-xs-12 col-sm-6 col-lg-6">
                            <div class="text-right">
                                <a href="javascript:void(0)" onClick="javascript:history.back(-1);"><button type="button" class="btn btn-default btn-bgcolor-white">Cancel</button></a>
                                <button type="submit" class="btn btn-default btn-bgcolor-blue" form="if">Save Coupon</button>
                            </div>	
                        </div>	
                    </div>

                </div>
                <div id="breadcrumb">
                    <a href="/coupons" title="Go to Coupon List" class="tip-bottom"><i class="fa fa-tags"></i>Coupon</a>
                    <a class="current">Add a Coupon</a>
                </div>
                <div class="row">
                    <form method="post" id="if" action="<?php echo site_url('coupons/insert') ?>" onsubmit="return check_sub()">
                        <div class="discountscontent-box">
                            <div class="col-xs-12 col-sm-3 col-lg-3">
                                <div class="discountscontent-box-left">
                                    <h4>Coupon details</h4>
                                    <h6 class="subdued discountscontent-box-left-Create">Create your Coupon code, and specify the usage limit.</h6>
                                    <button type="button" class="btn btn-default btn-bgcolor-white" id="creatCode" >Generate code</button>
                                </div>
                            </div>	
                            <div class="col-xs-12 col-sm-9 col-lg-9">
                                <div class="discountscontent-box-right">
                                    <h5><b>Coupon code</b></h5>
                                    <input type="text" class="form-control" name="coupons_id" id="coupons_id" placeholder="6 or more keys">
                                    <h5><b>How many times can this Coupon be used?</b></h5>
                                    <h5><input type="text" class="group-radius-left" name="frequency"><span class="group-radius-right"><input type="checkbox" name="frequencyLimit" value="1">No limit</span></h5>
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
                                        <option value="1">$ AUD</option>
                                        <option value="2">% Discount</option>
                                        <option value="3">Free Shipping</option>
                                    </select>
                                    <h5 class="discountscontent-box-discounttype-h5">
                                        <small>Take</small><input type="text" class="group-radius-left" name="amount"><span class="group-radius-right">$</span><small>off for</small>
                                    </h5>
                                    <select class="form-control" id="condition" name="condition">
                                        <option value="1">all orders</option>
                                        <option value="2">Order Price Between</option>
                                        <option value="3">specific product</option>
                                        <!--                                        <option>collection</option>
                                                                                <option>specific product</option>
                                                                                <option>customer in group</option>-->
                                    </select>

                                    <div class="form-group col-lg-5" style="display:none">
                                        <input type="text" class="form-control tags-rightinput" placeholder="Vintage, cotton, summer">
                                    </div>

                                    <div class="form-group col-lg-5" style="display:none">
                                        <div class="input-group" style="padding-top:5px">
                                            <input type="text" class="form-control"  placeholder="Min" name="min">
                                            <div class="input-group-addon">to</div>
                                            <input type="text" class="form-control"  placeholder="Max" name="max">
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
                                                <input id="ui-datepicker" type="text" class="form-control input-sm" name="start" />
                                            </h5>
                                        </div>
                                        <div class="col-xs-12 col-sm-8 col-lg-8">
                                            <b>Coupon expires (end of day):</b> 
                                            <h5>
                                                <input id="ui-datepicker-2" type="text" class="group-radius-left" name="end" />
                                                <span class="group-radius-right ui-datepicker-2span"><input type="checkbox" name="neverExpires" value="1">Never expires</span>
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
                                            <textarea  class="form-control" name="note"></textarea>
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

                                            <input type="radio" class="form-control" value="2" name="private" checked="checked" />
                                            <span>All user</span>
                                        </div>
                                        <div class="col-xs-12 col-sm-8 col-lg-8 discount Specific_User">

                                            <input type="radio" class="form-control" value="1" name="private" />
                                            <span>Specific User</span>
                                            <!--<h5><input type="text" style="display:none" name="emailList" /></h5>-->    
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
                                    <h6 class="subdued discountscontent-box-left-Create">个人中心不会显示这个优惠券存在，客户只有知道这个优惠券存在才可用。</h6>
                                </div>
                            </div>  
                            <div class="col-xs-12 col-sm-9 col-lg-9">
                                <div class="discountscontent-box-right">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3 col-lg-3 discount All_user"> 

                                            <input type="radio" class="form-control" value="1" name="display" checked="checked" />
                                            <span>所有用户可见</span>
                                        </div>
                                        <div class="col-xs-12 col-sm-8 col-lg-8 discount Specific_User">

                                            <input type="radio" class="form-control" value="2" name="display" />
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
                                <button type="sumbit" class="btn btn-default btn-bgcolor-blue">Save Coupon</button>
                            </div>	
                        </div>
                    </form>
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
                                    function check_sub() {
                                        if ($('#coupons_id').val().length < 6 || $('#coupons_id').val().length > 15) {
                                            alert('请输入6到15位优惠卷码');
                                            return false;
                                        }
                                    }
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
                                            ;
                                        });

                                        $("#creatCode").on("click", function () {

                                            $.post("<?php echo site_url('coupons/getCouponsCode') ?>", function (result) {
                                                $("input[name='coupons_id']").val(result.coupons_id);
                                            }, 'json');
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
                                        });
                                    });
        </script>        
    </body>
</html>
