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

            <div id="content" data-model="<?php echo $doc['model'] ?>">
                <form method="post" action="<?php echo site_url('collection/update') ?>">
                    <input type="hidden" name="collection_id" value="<?php echo $collection_id ?>" >
                    <input type="hidden" name="model" value="<?php echo $doc['model'] ?>">

                    <div id="content-header" class="mini">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-lg-6">
                                <h1>Collection</h1>
                            </div>	
                            <div class="col-xs-12 col-sm-6 col-lg-6">
                                <div class="pull-right">
                                    <?php $www = $domain;?>
                                    <button type="button" class="btn btn-default btn-bgcolor-white" onclick="window.location.href='/collection'">Cancel</button>
                                    <a class="btn btn-default btn-bgcolor-white" href="<?='http://'.$www.'/collections/'.$doc['seo_url']?>" target="_blank"><span class="fa fa-external-link fa-products-icon" aria-hidden="true"></span> view detail</a>
                                    <button type="submit" class="btn btn-default btn-bgcolor-blue">Save collection</button>
                                </div>	
                            </div>	
                        </div>

                    </div>
                    <div id="breadcrumb">
                        <a href="/collection" title="Go to Collection List" class="tip-bottom"><i class="fa fa-tags"></i>Collection</a>
                        <a class="current">Edit a Collection</a>
                    </div>
                    <div class="row">

                        <div class="col-xs-12 col-sm-8 col-lg-8">
                            <div class="widget-box widget-box-hledit widget-box-hledit-productadd-left">

                                <div class="form-group">
                                    <label for="producttitle">Title</label>
                                    <input type="producttitle" class="form-control" id="producttitle" name='title' placeholder="e.g. Summer collection, Under $100, Staff picks" value="<?php echo $doc['title']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="Description">Description</label>
                                    <textarea class="summernote" name='description'><?php echo $doc['description'] ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="Description2">Description2</label>
                                    <textarea class="summernote" name='description2'><?php echo isset($doc['description2'])?$doc['description2']:''; ?></textarea>
                                </div>

                            </div>



                            <div class="widget-box widget-box-hledit widget-box-hledit-productadd-left widget-box-hledit-productadd-left-Pricingbox">
                                <?php if ($doc['model'] == 2): ?>
                                    <div class="widget-title"> 
                                        <div class="col-sm-12 collectionadd-Conditions">
                                            <h4 class="collectionadd-showhideh4">Conditions</h4>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="clearfix"></div>
                                <div class="widget-content nopadding collectionadd-inputbox-box">
                                    <?php if ($doc['model'] == 2): ?>

                                        <div id="showhide-box">
                                            <div class="col-xs-12 collectionadd-match-box">
                                                <h6 class="col-sm-3 showhide-box-h6">Products must match:</h6>
                                                <div class="radio col-sm-3">
                                                    <label>
                                                        <input type="radio" name="relation" value='and' <?php if ($doc['relation'] == 'and') echo 'checked' ?>>all conditions 
                                                    </label>
                                                </div>
                                                <div class="radio col-sm-3">
                                                    <label>
                                                        <input type="radio" name="relation" value='or' <?php if ($doc['relation'] == 'or') echo 'checked' ?>>any condition
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-xs-12">
                                                <div class="collectionadd-inputbox-all">
                                                    <?php
                                                    $one = TRUE;
                                                    foreach ($doc['conditions'] as $conditions) {
                                                        if ($one) {
                                                            echo '<div class="collectionadd-hidebox-inputbox-one">';
                                                        }
                                                        ?>
                                                        <div class="collectionadd-hidebox-inputbox">  
                                                            <div class="col-sm-4"> 
                                                                <select class="form-control" name='fields[]'>
                                                                    <option value="type" <?php if ($conditions['fields'] == 'type') echo 'selected="selected"' ?> >Product type</option>
                                                                    <option value="tag.Tag3" <?php if ($conditions['fields'] == 'tag.Tag3') echo 'selected="selected"' ?>>Product tag</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <select class="form-control" name='link[]'>
                                                                    <option value="equals" <?php echo $conditions['link'] == 'equals' ? 'selected="selected"' : '' ?>>is equal to</option>
                                                                    <option value="contains" <?php echo $conditions['link'] == 'contains' ? 'selected="selected"' : '' ?>>contains</option>
                                                                </select>											
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <select class="form-control" name='values[]'>
                                                                    <?php
                                                                    $showData = $conditions['fields'] == 'type' ? $categories : $tag3s;
                                                                    foreach ($showData as $data) {
                                                                        if ($conditions['values'] == $data['_id']) {
                                                                            echo '<option selected="selected" value="' . $data['_id'] . '">' . $data['title'] . '</option>';
                                                                        } else {
                                                                            echo '<option value="' . $data['_id'] . '">' . $data['title'] . '</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                    <?php ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-sm-1"> 
                                                                <button type="button" class="btn btn-default btn-bgcolor-write collectionadd-delete">
                                                                    <i class="fa fa-trash-o fa-lg"></i>
                                                                </button>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <?php
                                                        if ($one) {
                                                            echo '</div>';
                                                            $one = FALSE;
                                                        }
                                                    }
                                                    ?>

                                                </div>
                                            </div>
                                            <div class="Addoptionbox-collection col-sm-12">
                                                <button type="button" class="btn btn-default btn-bgcolor-white" id="Addoptionbtn">Add another option</button>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="clearfix"></div>	
                                </div>
                            </div>	
                            <div class="widget-box widget-box-hledit widget-box-hledit-productadd-left widget-box-hledit-productadd-left-Pricingbox">
                                <div class="widget-title"> 
                                    <div class="col-sm-12 collectionadd-Conditions">
                                        <h4 class="collectionadd-showhideh4">Product</h4>
                                        <div class="row nopadding">
                                            <!--                                            <div class="col-sm-6 col-lg-6 nopadding">
                                                                                            <div class="input-group">
                                                                                                <input type="text" class="form-control" placeholder="search for ...">
                                                                                                <span class="input-group-btn">
                                                                                                    <button class="btn btn-default" type="button">Go!</button>
                                                                                                </span>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-lg-1 col-sm-1"></div>-->
                                            <div class="input-group col-sm-5 col-lg-5 nopadding">
                                                <div class="input-group">
                                                    <span class="input-group-btn">Sort： </span>
                                                    <select name="sort" id="sort" class="form-control">
                                                        <option value="manual" <?php if ($doc['sort'] == "manual") echo 'selected="selected"' ?>>Manually</option>
                                                        <option value="sold.total,-1" <?php if ($doc['sort'] == "sold.total,-1") echo 'selected="selected"' ?>>By best selling</option>
                                                        <!--<option value="title,1" <?php if ($doc['sort'] == "title,1") echo 'selected="selected"' ?>>Alphabetically: A-Z</option>-->
                                                        <!--<option value="title,-1" <?php if ($doc['sort'] == "title,-1") echo 'selected="selected"' ?>>Alphabetically: Z-A</option>-->
                                                        <option value="price,-1" <?php if ($doc['sort'] == "price,-1") echo 'selected="selected"' ?>>By price: Highest to lowest</option>
                                                        <option value="price,1" <?php if ($doc['sort'] == "price,1") echo 'selected="selected"' ?>>By price: Lowest to highest</option>
                                                        <option value="create_time,-1" <?php if ($doc['sort'] == "create_time,-1") echo 'selected="selected"' ?>>By date: Newest to oldest</option>
                                                        <option value="create_time,1" <?php if ($doc['sort'] == "create_time,1") echo 'selected="selected"' ?>>By date: Oldest to newest</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>                                
                                <div class="widget-content col-sm-12 collectionadd-Conditions">
                                    <ul class="collectionAdd-sortable">
                                        <?php if ($doc['sort'] == 'manual'): ?>
                                            <?php foreach ($doc['sortProductID'] as $productID) : 
                                                $img = IMAGE_DOMAIN.'/product/'.$doc['allow'][(string) $productID]['sku'].'/'.$doc['allow'][(string) $productID]['sku'].'.jpg';
                                                if(!@fopen($img,'r')){
                                                    $img = IMAGE_DOMAIN . $doc['allow'][(string) $productID]['image'];
                                                }
                                                ?>
                                                <li id="<?php echo (string) $productID ?>">
                                                    <table class="table table-hover">
                                                        <tr>
                                                            <td class="text-center"><i class="fa fa-ellipsis-v"></i></td>
                                                            <td><a href="/product/edit/<?php echo (string)$productID;?>"><img src="<?php echo $img; ?>" alt="" width="40"></a></td>
                                                            <td><a href="/product/edit/<?php echo (string)$productID;?>"><?php echo htmlspecialchars_decode($doc['allow'][(string) $productID]['title']) ?></a></td>
                                                            <td><i class="fa fa-times" data-bind="<?php echo (string) $productID ?>"></i></td>
                                                        </tr>
                                                    </table>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <?php foreach ($doc['allow'] as $product) : 
                                                $img = IMAGE_DOMAIN.'/product/'.$product['sku'].'/'.$product['sku'].'.jpg';
                                                if(!@fopen($img,'r')){
                                                    $img = IMAGE_DOMAIN . $product['image'];
                                                }
                                                ?>
                                                <li id="<?php echo (string) $product['_id'] ?>">
                                                    <table class="table table-hover">
                                                        <tr>
                                                            <td class="text-center"><i class="fa fa-ellipsis-v"></i></td>
                                                            <td><a href="/product/edit/<?php echo (string)$product['_id'];?>"><img src="<?php echo $img; ?>" alt="" width="40"></a></td>
                                                            <td><a href="/product/edit/<?php echo (string)$product['_id'];?>"><?php echo htmlspecialchars_decode($product['title']) ?></a></td>
                                                            <td><i class="fa fa-times" data-bind="<?php echo (string) $product['_id'] ?>"></i></td>
                                                        </tr>
                                                    </table>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>


                                    </ul>
                                    <!--                                    <div class="row text-center col-lg-12">
                                                                            <a href="#" class="btn">Show more products</a>
                                                                        </div>-->
                                </div>
                                <div class="clearfix"></div>
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
                                        <input name="seo_title" type="text" class="form-control" id="Pagetitleinput" value="<?php echo $doc['seo_title'] ?>">
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label for="Metadescription">Meta description</label>
                                        <textarea name="seo_description" class="form-control" id="Metadescription" rows="3"><?php echo $doc['seo_description'] ?></textarea>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <div class="input-group nopadding">
                                            <div class="input-group-addon">http://www.drgrab.com/collections/</div>
                                            <input name="seo_url" type="text" class="form-control" id="URLandhandle" placeholder="AboutFreeShipping" value="<?php echo $doc['seo_url'] ?>">
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
                                            <select class="selectbox" name="status">
                                                <option value="2" <?php if ($doc['status'] == "2") echo 'selected="selected"' ?>>Active</option>
                                                <option value="1" <?php if ($doc['status'] == "1") echo 'selected="selected"' ?>>Hidden</option>
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
                                                <option value="2" <?php if ($doc['columns'] == "2") echo 'selected="selected"' ?>>2列</option>
                                                <option value="3" <?php if ($doc['columns'] == "3") echo 'selected="selected"' ?>>3列</option>
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
                                                <option value="2" <?php if ($doc['show_comment'] == "2") echo 'selected="selected"' ?>>Show</option>
                                                <option value="1" <?php if ($doc['show_comment'] == "1") echo 'selected="selected"' ?>>Hidden</option>
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
                                        <textarea name="seo_keyword" class="form-control" placeholder="HTML,CSS,XML,JavaScript"><?php echo $doc['seo_keyword'] ?></textarea>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="dg-country-choose">
                                                <?php foreach ($languages as $key => $value): ?>
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
        <script src="js/jquery.ui.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.icheck.min.js"></script>
        <script src="js/select2.min.js"></script>
        <script src="js/fileinput.min.js"></script>
        <script src="js/sortable.min.js"></script>
        <!--左侧nav-->
        <script src="js/jquery.nicescroll.min.js"></script>
        <script src="js/unicorn.js"></script>
        <!--拖动插件-->

        <!--文本编辑器-->
        <script src="js/summernote.js"></script>

        <?php echo $foot ?>

        <script type="text/javascript">
            $(function () {
                //seo content
                $("#producttitle").bind('keyup', function () {
                    $val = $(this).val();
                    $("#Pagetitleinput").val($val.substr(0, 60));
                    /*$("#URLandhandle").val($val.replace(/\s+/g,'-').substr(0,60));*/
                });
                /*$("#URLandhandle").on('blur',function(){
                 $(this).attr('id','');
                 });*/
            });
            //collection产品列表特效
            //collection产品列表特效
            if ($('#sort').val() == 'manual') {
                $('.collectionAdd-sortable').sortable({
                    opacity: 0.3, //拖动的透明度 
                    revert: true, //缓冲效果 
                    cursor: 'move', //拖动的时候鼠标样式 
                    delay: 1,
                    stop: function () {
                        var cty = [];
                        $(".dg-country-choose input[name^='lang-'").each(function () {
                            if(this.checked){
                                cty.push(this.value);
                            }
                        });
                        $val = $(".collectionAdd-sortable").sortable('toArray');
                        $.post('<?php echo site_url('collection/updateSort') ?>', {
                            collection_id: '<?php echo $collection_id ?>',
                            keyList: $val,
                            cty:cty.join(',')
                        }, function (result) {
                            if (!result.success) {
                                alert('排序失败！！！');
                            }
                        }, 'json');
                    }
                });
            } else {
                $('.collectionAdd-sortable').sortable();
                $('.collectionAdd-sortable').sortable('disable');
            }



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
            });
            $("#Addoptionbtn").on('click', function () {
                $(".collectionadd-hidebox-inputbox-one .collectionadd-hidebox-inputbox").clone().appendTo(".collectionadd-inputbox-all").children('div').eq(0).children('select').bind('change', function () {
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
                });
            });
            $(".fa-times").on('click', function () {
                var that = this;
                $.post('<?php echo site_url('collection/removeProduct') ?>', {
                    collection_id: '<?php echo $collection_id ?>',
                    product_id: $(that).data('bind')
                }, function (result) {
                    if (result.success) {
                        $(that).closest('li').detach();
                    } else {
                        alert(result.error);
                    }
                }, 'json');
            });
            $(document).on('click', '.collectionadd-delete', function () {
                if (!$(this).parent().parent().parent().hasClass('collectionadd-hidebox-inputbox-one')) {
                    $(this).parent().parent(".collectionadd-hidebox-inputbox").remove();
                }
            });
//            $("#productadd-website-seo").click(function () {
//                $("#productadd-website-seo-hidebox").animate({
//                    height: 'toggle', opacity: 'toggle'
//                }, 300);
//                return false;
//            })
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
            $('#sort').on('change', function () {
                if (parseInt($('#content').data('model')) === 1 || $(this).val() === 'manual') {
                    $val = $(".collectionAdd-sortable").sortable('toArray');
                } else {
                    $val = '';
                }
                var cty = [];
                $(".dg-country-choose input[name^='lang-'").each(function () {
                    if(this.checked){
                        cty.push(this.value);
                    }
                });
                $.post('<?php echo site_url('collection/changeSort') ?>', {
                    model:<?php echo $doc['model'] ?>,
                    collection_id: '<?php echo $collection_id ?>',
                    sort: $(this).val(),
                    keyList: $val,
                    cty:cty.join(',')
                }, function (result) {
                    if (result.success) {
                        //location.reload();
                    } else {
                        alert(result.error);
                    }
                }, 'json');
            });



        </script>

    </body>
</html>
