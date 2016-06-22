<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo $template ?>">
        <title>Unicorn Admin</title>
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
            <?php echo $head; ?>

            <div id="content">
                <form method="post" action="<?php echo site_url('collection/insert') ?>">
                    <div id="content-header" class="mini">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-lg-6">
                                <h1>Collection</h1>
                            </div>	
                            <div class="col-xs-12 col-sm-6 col-lg-6">
                                <div class="text-right">
                                    <button type="button" class="btn btn-default btn-bgcolor-white" onclick="window.location.href='/collection'">Cancel</button>
                                    <button type="submit" class="btn btn-default btn-bgcolor-blue">Save collection</button>
                                </div>	
                            </div>	
                        </div>

                    </div>
                    <div id="breadcrumb">
                        <a href="/collection" title="Go to Collection List" class="tip-bottom"><i class="fa fa-tags"></i>Collection</a>
                        <a class="current">Add a Collection</a>
                    </div>
                    <div class="row">

                        <div class="col-xs-12 col-sm-8 col-lg-8">
                            <div class="widget-box widget-box-hledit widget-box-hledit-productadd-left">

                                <div class="form-group">
                                    <label for="producttitle">Title</label>
                                    <input type="producttitle" class="form-control" id="producttitle" name='title' placeholder="e.g. Summer collection, Under $100, Staff picks">
                                </div>
                                <div class="form-group">
                                    <label for="Description">Description</label>
                                    <textarea class="summernote" name='description'></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="Description2">Description2</label>
                                    <textarea class="summernote" name='description2'></textarea>
                                </div>

                            </div>


                            <div class="widget-box widget-box-hledit widget-box-hledit-productadd-left widget-box-hledit-productadd-left-Pricingbox">

                                <div class="widget-title"> 
                                    <div class="col-sm-12 collectionadd-Conditions">
                                        <h4 class="collectionadd-showhideh4">Conditions</h4>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="model" id="collectionadd-Radios1" value="1">
                                                Manually select products <span class="productadd-subdued">(you will be able to select products on the next page)</span>
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="model" id="collectionadd-Radios2" value='2' checked>
                                                Automatically select products based on conditions
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="widget-content nopadding collectionadd-inputbox-box">

                                    <div id="showhide-box">
                                        <div class="col-xs-12 collectionadd-match-box">
                                            <h6 class="col-sm-3 showhide-box-h6">Products must match:</h6>
                                            <div class="radio col-sm-3">
                                                <label>
                                                    <input type="radio" name="relation" value='and'>all conditions 
                                                </label>
                                            </div>
                                            <div class="radio col-sm-3">
                                                <label>
                                                    <input type="radio" name="relation" value='or' checked>any condition
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="collectionadd-inputbox-all">
                                                <div class="collectionadd-hidebox-inputbox-one">
                                                    <div class="collectionadd-hidebox-inputbox">  
                                                        <div class="col-sm-4"> 
                                                            <select class="form-control" name='fields[]'>
                                                                <option value="type" >Product type</option>
                                                                <option value="tag" >Product tag</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <select class="form-control" name='link[]'>
                                                                <option value="equals" selected>is equal to</option>
                                                                <option value="contains">contains</option>
                                                            </select>											
                                                        </div>
                                                        <div class="col-sm-3 selectbox2"> 
                                                            <select class="selectbox" name='values[]'>
                                                                <?php foreach ($categoryArr as $category) : ?>
                                                                    <option value="<?php echo $category['_id'] ?>"><?php echo $category['title'] ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-1"> 
                                                            <button type="button" class="btn btn-default btn-bgcolor-write collectionadd-delete">
                                                                <i class="fa fa-trash-o fa-lg"></i>
                                                            </button>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="Addoptionbox-collection col-sm-12">
                                            <button type="button" class="btn btn-default btn-bgcolor-white" id="Addoptionbtn">Add another option</button>
                                        </div>
                                    </div>  
                                    <div class="clearfix"></div>	
                                </div>
                            </div>	

                            <div class="widget-box widget-box-hledit widget-box-hledit-productadd-left">
                                <div class="widget-title">
                                    <h4 class="productadd-seotitle col-sm-9">SEO meta edit</h4>
                                    <!--a href="#" class="col-sm-3 productadd-a text-right" id="productadd-website-seo">Edit website SEO</a-->
                                </div>
                                <div class="widget-content nopadding">
                                    <h6 class="productadd-subdued">Add a title and description to see how this product might appear in a search engine listing.</h6>
                                </div>
                                <div id="productadd-website-seo-hidebox">
                                    <div class="form-group col-sm-12">
                                        <label for="Pagetitleinput">Page title</label>
                                        <input name="seo_title" type="text" class="form-control" id="Pagetitleinput" value="">
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label for="Metadescription">Meta description</label>
                                        <textarea name="seo_description" class="form-control" id="Metadescription" rows="3"></textarea>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label for="URLandhandle">URL andhandle</label>
                                        <div class="input-group nopadding">
                                            <div class="input-group-addon">http://www.drgrab.com/collection/</div>
                                            <input name="seo_url" type="text" class="form-control" id="URLandhandle" placeholder="AboutFreeShipping">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>	
                        </div>
                        <div class="col-xs-12 col-sm-4 col-lg-4">
                            <div class="widget-box widget-box-hledit widget-box-hledit-productcontent-right">
                                <div class="widget-title">
                                    <h4>Status</h4>
                                </div>
                                <div class="widget-content nopadding">
                                    <div class="productcontent-right-onlinestorebox pull-left">
                                        <div class="productcontent-right-onlinestorebox-left">
                                            <select class="selectbox" name="status" disabled="disabled">
                                                <!--<option>Active</option>-->
                                                <option value="1">Hidden</option>
                                            </select>
                                        </div> 
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="widget-box widget-box-hledit widget-box-hledit-productcontent-right">
                                <div class="widget-title">
                                    <h4>Number of columns</h4>
                                </div>
                                <div class="widget-content nopadding">
                                    <div class="productcontent-right-onlinestorebox pull-left">
                                        <div class="productcontent-right-onlinestorebox-left">
                                            <select class="selectbox" name="columns">
                                                <option value="3">3列</option>
                                                <option value="2">2列</option>
                                                <!--<option value="4">4列</option>-->
                                            </select>
                                        </div> 
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="widget-box widget-box-hledit widget-box-hledit-productcontent-right">
                                <div class="widget-title">
                                    <h4>Comment</h4>
                                </div>
                                <div class="widget-content nopadding">
                                    <div class="productcontent-right-onlinestorebox pull-left">
                                        <div class="productcontent-right-onlinestorebox-left">
                                            <select class="selectbox" name="show_comment">
                                                <option value="2">Show</option>
                                                <option value="1">Hidden</option>
                                            </select>
                                        </div> 
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="widget-box widget-box-hledit widget-box-hledit-productcontent-right">
                                <div class="widget-title">
                                    <h4>New product position</h4>
                                </div>
                                <div class="widget-content nopadding">
                                    <div class="form-group">
                                        <input name="newlast" type="checkbox" value="1"<?php if (isset($doc['newlast'])&&$doc['newlast'] == 1) echo " checked"; ?>>
                                        <span style="vertical-align: super;">new product to last</span>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="widget-box widget-box-hledit widget-box-hledit-productcontent-right">
                                <div class="widget-title">
                                    <h4>Keyword</h4>
                                </div>
                                <div class="widget-content nopadding">
                                    <div class="form-group">
                                        <textarea name="seo_keyword" class="form-control" placeholder="HTML,CSS,XML,JavaScript"></textarea>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                                    <div class="dg-country-choose">
                                        <?php foreach ($language as $key => $value): ?>
                                            <input type="checkbox" class="select-all" data-lang="<?php echo $key ?>"/> <?php echo $value ?> <br/>
                                            <?php foreach ($country[$key] as $country_code): ?>
                                                <input type="checkbox" name="lang-<?php echo $key ?>[]" value='<?php echo $country_code ?>'/> <img src="img/flag/<?php echo $country_code ?>.png">
                                            <?php endforeach; ?>
                                            <br /><br />
                                        <?php endforeach; ?>
                                    </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="produccontent-buttomsave-box pull-left">
                            <div class="pull-right">
                                <button type="button" class="btn btn-default btn-bgcolor-white" onclick="window.location.href='/collection'">Cancel</button>
                                <button type="sumbit" class="btn btn-default btn-bgcolor-blue">Save collection</button>
                            </div>
                        </div>
                    </div> 
                </form>
            </div>
        </div>
        <div class="row">
            <div id="footer" class="col-xs-12"></div>
        </div>


        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.icheck.min.js"></script>
        <script src="js/select2.min.js"></script>
        <script src="js/fileinput.min.js"></script>
        <script src="js/sortable.min.js"></script>
        <!--左侧nav-->
        <script src="js/jquery.nicescroll.min.js"></script>
        <script src="js/unicorn.js"></script>
        <!--拖动插件-->
        <script src="js/jquery.sortable.min.js"></script>

        <!--文本编辑器-->
        <script src="js/summernote.js"></script>

        <?php echo $foot ?>

        <script type="text/javascript">
            $(function () {
                //seo content
                $("#producttitle").bind('keyup', function () {
                    $val = $(this).val();
                    $("#Pagetitleinput").val($val.substr(0, 60));
                    $("#URLandhandle").val($val.replace(/\s+/g, '-').substr(0, 60));
                });
                $("#URLandhandle").on('blur', function () {
                    $(this).attr('id', '');
                });
                $('.summernote').on('summernote.keyup', function () {
                    $('#Metadescription').text((($(this).code()).replace(/<.+?>/gi, "")).substr(0, 160));
                });
            });
            //collection产品列表特效
            $('.collectionAdd-sortable').sortable();
            $('input[type=radio]').iCheck({
                checkboxClass: "icheckbox_flat-blue",
                radioClass: "iradio_flat-blue"
            });
            $("#collectionadd-Radios1").on("ifChecked", function (event) {
                $(".collectionadd-showhideh4").text("Select products");
                $("#showhide-box").hide();
                return false;
            });
            $("#collectionadd-Radios2").on("ifChecked", function (event) {
                $(".collectionadd-showhideh4").text("Conditions");
                $("#showhide-box").show();
                return false;
            });

            $('.collectionadd-hidebox-inputbox div:first-child select').on('change', function () {
                $that = this;
                $val = $(this).val();
                if ($val == 'type') {
                    $val = 'category'
                }
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>" + "dropdown/" + $val,
                    dataType: 'html',
                    success: function (result) {
                        $($that).parents().siblings('.col-sm-3').children('select').html(result);
                    }
                });
                $(this).parents().siblings('.col-sm-3').find('span.select2-chosen').text('Write your words...');
            });
            $("#Addoptionbtn").on('click', function () {
                $(".collectionadd-hidebox-inputbox-one .collectionadd-hidebox-inputbox").clone().appendTo(".collectionadd-inputbox-all").find('.selectbox2').html('<select class=\"selectbox\" name=\"values[]\"><?php foreach ($categoryArr as $category) : ?><option value="<?php echo $category['_id'] ?>"><?php echo htmlspecialchars($category['title'], ENT_QUOTES); ?></option><?php endforeach; ?><\/select>').siblings('div').eq(0).children('select').bind('change', function () {
                    $that = this;
                    $val = $(this).val();
                    if ($val == 'type') {
                        $val = 'category'
                    }
//                    if ($val == 'tag') {
//                        $(this).children("div").eq(2).children('select').html('');
//                    } else if ($val == 'type') {
//                        $(this).children("div").eq(2).children('select').html('<option>test</option>');
//                    }
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>" + "dropdown/" + $val,
                        dataType: 'html',
                        success: function (result) {
                            $($that).parents().siblings('.col-sm-3').children('select').html(result);
                        }

                    });
                    $(this).parents().siblings('.col-sm-3').find('span.select2-chosen').text('Write your words...');
                });
                $('.collectionadd-inputbox-all .collectionadd-hidebox-inputbox').not($('.collectionadd-inputbox-all .collectionadd-hidebox-inputbox').eq(0)).find(".selectbox2 select").select2();
            });

            $(document).on('click', '.collectionadd-delete', function () {
                if (!$(this).parent().parent().parent().hasClass('collectionadd-hidebox-inputbox-one')) {
                    $(this).parent().parent(".collectionadd-hidebox-inputbox").remove();
                }
            });

            $("#productadd-website-seo").click(function () {
                $("#productadd-website-seo-hidebox").animate({
                    height: 'toggle', opacity: 'toggle'
                }, 300);
                return false;
            })
            $('.selectbox').select2();

            $('.select-all').click(function (event) {
                var lang = $(this).data('lang')
                if (this.checked) {
                    $("input[name='lang-" + lang + "[]']").each(function () {
                        this.checked = true;
                    });
                } else {
                    $("input[name='lang-" + lang + "[]']").each(function () {
                        this.checked = false;
                    });
                }
            });

        </script>

    </body>
</html>
