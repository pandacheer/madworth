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
                            <h1><i class="glyphicon glyphicon-gift" aria-hidden="true"></i>Edit a Discount</h1>
                        </div>  
                        <div class="col-xs-12 col-sm-6 col-lg-6">
                            <div class="text-right">
                                <a href="javascript:void(0)" onClick="javascript:history.back(-1);"><button type="button" class="btn btn-default btn-bgcolor-white">Cancel</button></a>
                                <button type="submit" class="btn btn-default btn-bgcolor-blue" form="if">Save Discount</button>
                            </div>  
                        </div>  
                    </div>

                </div>
                <div id="breadcrumb">
                    <a href="/coupons" title="Go to Discount List" class="tip-bottom"><i class="fa fa-tags"></i>Discount</a>
                    <a class="current">Add a Discount</a>
                </div>
                <div class="row">
                    <form method="post" id="if" action="<?php echo site_url('discount/update') ?>" onsubmit="return check_sub()">

                        <div class="discountscontent-box discountscontent-box-daterange">
                            <div class="col-xs-12 col-sm-3 col-lg-3">
                                <div class="discountscontent-box-left">
                                    <h4>Collection</h4>
                                    <h6 class="subdued discountscontent-box-left-Create">不能修改</h6>
                                </div>
                            </div>  
                            <div class="col-xs-12 col-sm-9 col-lg-9">
                                <div class="discountscontent-box-right">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <input type="hidden" name="collection_id" value="<?php echo $collection['_id'] ?>" />
                                            <input type="text" class="form-control" value="<?php echo $collection['title'] ?>" readonly="readonly">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>


                        <div class="discountscontent-box discountscontent-box-daterange">
                            <div class="col-xs-12 col-sm-3 col-lg-3">
                                <div class="discountscontent-box-left">
                                    <h4>Discount condition</h4>
                                    <h6 class="subdued discountscontent-box-left-Create">Choose the Discount condition.</h6>
                                </div>
                            </div>  
                            <div class="col-xs-12 col-sm-9 col-lg-9">
                                <div class="discountscontent-box-right">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3 col-lg-3 discount All_user">  

                                            <input type="radio" class="form-control" value="1" name="condition" <?php if ($discount['condition'] == 1) echo ' checked="checked"' ?> />
                                            <span>Amount of money</span>
                                        </div>
                                        <div class="col-xs-12 col-sm-8 col-lg-8 discount Specific_User">

                                            <input type="radio" class="form-control" value="2" name="condition" <?php if ($discount['condition'] == 2) echo ' checked="checked"' ?> />
                                            <span>Products Number</span>    
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="discountscontent-box discountscontent-box-daterange">
                            <div class="col-xs-12 col-sm-3 col-lg-3">
                                <div class="discountscontent-box-left">
                                    <h4>Discount Type</h4>
                                    <h6 class="subdued discountscontent-box-left-Create">Choose the Discount Type.</h6>
                                </div>
                            </div>  
                            <div class="col-xs-12 col-sm-9 col-lg-9">
                                <div class="discountscontent-box-right">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3 col-lg-3 discount All_user">  

                                            <input type="radio" class="form-control" value="1" name="type" <?php if ($discount['type'] == 1) echo ' checked="checked"' ?> />
                                            <span>Cash</span>
                                        </div>
                                        <div class="col-xs-12 col-sm-8 col-lg-8 discount Specific_User">

                                            <input type="radio" class="form-control" value="2" name="type" <?php if ($discount['type'] == 2) echo ' checked="checked"' ?> />
                                            <span>Percentage</span>    
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="discountscontent-box discountscontent-box-daterange">
                            <div class="col-xs-12 col-sm-3 col-lg-3">
                                <div class="discountscontent-box-left">
                                    <h4>Discount in detail</h4>
                                    <h6 class="subdued discountscontent-box-left-Create">逗号分隔，多个折扣回车换行</h6>
                                </div>
                            </div>  
                            <div class="col-xs-12 col-sm-9 col-lg-9">
                                <div class="discountscontent-box-right">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <textarea  class="form-control" name="detail"><?php echo substr($discount['detail'], 1, strlen($discount['detail']) - 2) ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="discountscontent-box discountscontent-box-daterange">
                            <div class="col-xs-12 col-sm-3 col-lg-3">
                                <div class="discountscontent-box-left">
                                    <h4>Discount Describe</h4>
                                    <h6 class="subdued discountscontent-box-left-Create"></h6>
                                </div>
                            </div>  
                            <div class="col-xs-12 col-sm-9 col-lg-9">
                                <div class="discountscontent-box-right">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <input type="text" class="form-control" name="title" value="<?php echo $discount['title'] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="text-right">
                            <a href="javascript:void(0)" onClick="javascript:history.back(-1);"><button type="button" class="btn btn-default btn-bgcolor-white">Cancel</button></a>
                            <button type="submit" class="btn btn-default btn-bgcolor-blue" form="if">Save Discount</button>
                        </div> 
                        <br> 
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
        <script src="js/select2.min.js"></script>
        <script src="js/jquery.icheck.min.js"></script>
        <!--左侧nav-->
        <script src="js/jquery.nicescroll.min.js"></script>
        <script src="js/unicorn.js"></script>

        <script src="js/unicorn.jui.js"></script>
        <?php echo $foot ?>

        <script>
                                $('.selectbox').select2();
                                $(function () {
                                    var checkboxClass = 'icheckbox_flat-blue';
                                    var radioClass = 'iradio_flat-blue';
                                    $("input[name='type']").iCheck({
                                        checkboxClass: checkboxClass,
                                        radioClass: radioClass
                                    });
                                    $("input[name='condition']").iCheck({
                                        checkboxClass: checkboxClass,
                                        radioClass: radioClass
                                    });
                                });
        </script>        
    </body>
</html>
