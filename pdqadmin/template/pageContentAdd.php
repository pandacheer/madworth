<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo $template ?>">
        <title>Discountscontent</title>
        <meta charset="UTF-8" />
        <!--<meta name="viewport" content="width=device-width, initial-scale=1.0" />-->
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/font-awesome.css" />	
        <link rel="stylesheet" href="css/jquery-ui.css" />
        <link rel="stylesheet" href="css/icheck/flat/blue.css" />
        <link rel="stylesheet" href="css/select2.css" />
        <link rel="stylesheet" href="css/unicorn.css" />
        <link rel="stylesheet" href="css/summernote.css" />
        <link rel="stylesheet" href="css/paddy.css" />
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
                            <h1><i class="glyphicon glyphicon-check" aria-hidden="true"></i>Add</h1>
                        </div>	
                    </div>

                </div>
                <div id="breadcrumb">
                    <a href="/admin/products" title="Go to Discounts List" class="tip-bottom"><i class="fa fa-tags"></i> Online Store </a>
                    <a href="/pages" class="current">Pages</a>
                    <a href="#" class="current">Add</a>
                </div>
                <div class="row AboutFreeShipping">
                    <?php echo form_open('pagesContent/addPages'); ?>
                        <div class="discountscontent-box discountscontent-box-daterange">
                            <div class="col-xs-12 col-sm-3 col-lg-3">
                                <div class="discountscontent-box-left">
                                    <h4>Write your page</h4>
                                    <h6 class="subdued discountscontent-box-left-Create">Give your page a title and add youe page content.</h6>
                                </div>
                            </div>  
                            <div class="col-xs-12 col-sm-9 col-lg-9">
                                <div class="discountscontent-box-right">
                                    <h5><b>Title</b></h5>
                                    <input type="text" class="form-control" id="page-title" name="pages_title" placeholder="e.g. Contact us, Sizing chart, FAQs" />
                                    <h5><b>Content</b></h5>
                                    <textarea class="summernote" name="pages_content"><p>Write <b>page content</b></p></textarea>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="discountscontent-box">
                            <div class="col-xs-12 col-sm-3 col-lg-3">
                                <div class="discountscontent-box-left">
                                    <h4>Search engines</h4>
                                    <h6 class="subdued discountscontent-box-left-Create">Set up the page title. meta description and handle.These help define how this page shows up on search engines.</h6>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-9 col-lg-9">
                                <div class="discountscontent-box-right">
                                    <h5><b>Page title</b><span><span id="pt">0</span> of 60 characters used</span></h5>
                                    <input type="text" class="form-control" id="page-title-seo" name="seo_title" placeholder="About Free Shipping" />
                                    <h5><b>Meta description</b><span><span id="md">0</span> of 160 characters used</span></h5>
                                    <textarea class="form-control" id="page-description" name="description" rows="3"></textarea>
                                    <h5><b>URL & Handle</b><span class="fa fa-question-circle"></span></h5>
                                    <div class="input-group nopadding">
                                      <div class="input-group-addon">http://www.drgrab.com/pages/</div>
                                      <input type="text" class="form-control" id="page-url-seo" name="url" placeholder="AboutFreeShipping">
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="discountscontent-box discountscontent-box-daterange">
                            <div class="col-xs-12 col-sm-3 col-lg-3">
                                <div class="discountscontent-box-left">
                                    <h4>Visibility</h4>
                                    <h6 class="subdued discountscontent-box-left-Create">Control if this page can be viewed on your storefront.</h6>
                                </div>
                            </div>	
                            <div class="col-xs-12 col-sm-9 col-lg-9">
                                <div class="discountscontent-box-right">
                                    <div class="row">
                                        <input type="radio" name="isShow" value="1"> Visible
                                    </div>
                                    <div class="row">
                                        <input type="radio" name="isShow" value="0" checked="checked"> Hidden
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 text-right">
                                    <button type="sumbit" class="btn btn-default btn-bgcolor-blue">Save</button>
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
        <!--左侧nav-->
        <script src="js/jquery.nicescroll.min.js"></script>
        <script src="js/unicorn.js"></script>

        <script src="js/unicorn.jui.js"></script>
        <!--文本编辑器-->
        <script src="js/summernote.js"></script>
        <?php echo $foot ?>
        <script type="text/javascript">
            $(function() {
              $("#page-title").bind('keyup',function(){
                    var $val = $(this).val();
                    $("#page-title-seo").val($val.substr(0,60));
                    $("#page-url-seo").val($val.replace(/\s+/g,'-').substr(0,60));
                    $val = $("#page-title-seo").val(),len = $val.length;
                    $('#pt').text(len);
                });
                $("#page-url-seo").on('blur',function(){
                    $(this).attr('id','asdf');
                });
                $('.summernote').on('summernote.keyup',function(){
                    $('#page-description').text(($(this).code()).replace(/<.+?>/gi , "").substr(0,160));
                });
                $("#page-title-seo").on('keyup blur',function(){
                    if($(this).val()){
                        $(this).val($(this).val().substr(0,60));
                    }
                    var $val = $(this).val(),len = $val.length;
                    $('#pt').text(len);
                })
                $("#page-description").on('keyup blur',function(){
                    if($(this).val()){
                        $(this).val($(this).val().substr(0,160));
                    }
                    var $val = $(this).val(),len = $val.length;
                    $('#md').text(len);
                })
            });
        </script>
    </body>
</html>
