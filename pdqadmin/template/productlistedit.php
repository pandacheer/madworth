<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Unicorn Admin</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <base href="<?php echo $template ?>">
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/font-awesome.css" />
        <link rel="stylesheet" href="css/jquery-ui.css" />
        <link rel="stylesheet" href="css/icheck/flat/blue.css" />
        <link rel="stylesheet" href="css/select2.css" />		
        <link rel="stylesheet" href="css/multiple-select.css" />		
        <link rel="stylesheet" href="css/unicorn.css" />
        <link rel="stylesheet" href="css/paddy.css" />
        <link rel="stylesheet" href="css/jquery.tagsinput.css" />
        <link rel="stylesheet" href="css/summernote.css" />
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
                            <h1>Products</h1>
                        </div>	
                        <div class="col-xs-12 col-sm-6 col-lg-6">
                            <div class="text-right">
                                <a href="/product" class="btn btn-default btn-bgcolor-white"> Back</a>
                                <a href="/product/add" class="btn btn-default btn-bgcolor-blue"><i class="fa fa-plus-circle"></i>Create a product</a>
                            </div>	
                        </div>	
                    </div>
                </div>
                <div id="breadcrumb">
                    <a href="/admin/products" title="Go to Product List" class="tip-bottom"><i class="fa fa-tags"></i> Products</a>
                    <a href="#" class="current">Product List</a>
                </div>				
                <div class="row">
                    <div class="col-xs-6">
                        <div class="widget-box widget-box-hledit">
                            <div class="widget-title">
                                <h3 class="widget-title-h3 pull-left">All Products</h3>
                                <div class="pull-right"><a href="/productCart/delAll" class="btn btn-default btn-bgcolor-white dg-product-batch-empty"> Empty the List</a></div>
                            </div>
                            <div class="widget-content nopadding">
                                <table class="table table-striped table-hover with-check">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th class="image"></th>
                                            <th>Product</th>
                                            <th>SKU</th>
                                            <th>Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($list as $vo) {
                                            switch ($vo['status']) {
                                                case 1 : $status = 'Normal';
                                                    break;
                                                case 2 : $status = 'Hidden';
                                                    break;
                                                case 3 : $status = 'Out Of Stock';
                                                    break;
                                                default :$status = '';
                                                    break;
                                            }
                                            $img = IMAGE_DOMAIN . '/product/' . $vo['sku'] . '/' . $vo['sku'] . '.jpg';
                                            if (!@fopen($img, 'r')) {
                                                if (is_array($vo['image'])) {
                                                    $vo['image'] = isset($vo['image'][0]) ? $vo['image'][0] : '';
                                                }
                                                $img = IMAGE_DOMAIN . $vo['image'];
                                            }
                                            echo '
										<tr>
											<td class="select"><a href="/productCart/del/' . $vo['_id'] . '"><i class="fa fa-close fa-lg"></i></a></td>
											<td class="image">
											<a href="/product/edit/' . $vo['_id'] . '"><img alt="' . $vo['title'] . '" class="block" src="' . $img . '" title="' . $vo['title'] . '"></a>
											</td>
											<td>
												<div class="tabletitle">
													<a href="/product/edit/' . $vo['_id'] . '">' . $vo['title'] . '</a>
													<h6 class="subdued">' . $status . '</h6>
												</div>
											</td>
											<td>' . $vo['sku'] . '</td>
											<td>' . $vo['type'] . '</td>
										</tr>
											';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="widget-box widget-box-hledit">
                            <div class="tabbable inline">
                                <ul class="nav nav-tabs tab-bricky" id="myTab">
                                    <li class="active">
                                        <a data-toggle="tab" href="#panel_tab2_example1">Collection</a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#panel_tab2_example2">Price</a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#panel_tab2_example3">Countdown</a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#panel_tab2_example4">Tag</a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#panel_tab2_example5">Status</a>
                                    </li>
                                    <li>
                                        <a data-toggle="tab" href="#panel_tab2_example6">RelativePro</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div id="panel_tab2_example1" class="tab-pane in active">
                                        <form method="post" action="/productCart/upCollection">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
                                                    <p>Create a new Collection</p>
                                                    <input type="text" name="newcollection" class="form-control" placeholder="Collection Name">
                                                </label>
                                            </div>
                                            <br/>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                                                    <p>Add them to an existing Collection</p>
                                                    <select class="js-example-basic-multiple" multiple="multiple">
                                                        <?php
                                                        foreach ($collection as $vo) {
                                                            echo '<option value="' . $vo['_id'] . '">' . $vo['title'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </label>
                                                <input name="existcollection" type="hidden" id="hiddenselectval">
                                            </div>
                                            <br />
                                            <div class="dg-country-choose">
                                                <?php foreach ($language as $key => $value): ?>
                                                    <input type="checkbox" class="select-all" data-lang="<?php echo $key ?>"/> <?php echo $value ?> <br/>
                                                    <?php foreach ($country[$key] as $country_code): ?>
                                                        <input type="checkbox" name="lang-<?php echo $key ?>[]" value='<?php echo $country_code ?>'/> <img src="img/flag/<?php echo $country_code ?>.png">
                                                    <?php endforeach; ?>
                                                    <br /><br />
                                                <?php endforeach; ?>
                                                <input type="submit" class="btn btn-default btn-bgcolor-blue" value="Submit">
                                            </div>
                                        </form>
                                    </div>
                                    <div id="panel_tab2_example2" class="tab-pane">
                                        <form method="post" action="/productCart/upPrice">
                                            <label>What to Do?</label>
                                            <select name="control" id="dg-product-price-add">
                                                <option value="1">Price +</option>
                                                <option value="2">Price -</option>
                                                <option value="3">Price To</option>
                                            </select>
                                            <input name="price" type="text" class="form-control dg-product-batch-price">
                                            <br />
                                            <br />
                                            <div class="dg-country-choose">
                                                <?php foreach ($language as $key => $value): ?>
                                                    <input type="checkbox" class="select-all" data-lang="<?php echo $key ?>"/> <?php echo $value ?> <br/>
                                                    <?php foreach ($country[$key] as $country_code): ?>
                                                        <input type="checkbox" name="lang-<?php echo $key ?>[]" value='<?php echo $country_code ?>'/> <img src="img/flag/<?php echo $country_code ?>.png">
                                                    <?php endforeach; ?>
                                                    <br /><br />
                                                <?php endforeach; ?>
                                                <input type="submit" class="btn btn-default btn-bgcolor-blue" value="Submit">
                                            </div>
                                        </form>
                                    </div>
                                    <div id="panel_tab2_example3" class="tab-pane">
                                        <form method="post" action="/productCart/upCountdown">
                                            <label>Add to an existing Countdown</label>
                                            <select name="countdown" id="dg-product-type-add">
                                                <?php
                                                foreach ($countdown as $vo) {
                                                    echo '<option value="' . $vo['id'] . '">' . $vo['name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <a href="/countdown/loadAddPage" class="btn btn-default btn-bgcolor-white dg-product-batch-countdown"><i class="fa fa-plus-circle"></i> Create a new Countdown</a>
                                            <br />
                                            <br />
                                            <div class="dg-country-choose">
                                                <?php foreach ($language as $key => $value): ?>
                                                    <input type="checkbox" class="select-all" data-lang="<?php echo $key ?>"/> <?php echo $value ?> <br/>
                                                    <?php foreach ($country[$key] as $country_code): ?>
                                                        <input type="checkbox" name="lang-<?php echo $key ?>[]" value='<?php echo $country_code ?>'/> <img src="img/flag/<?php echo $country_code ?>.png">
                                                    <?php endforeach; ?>
                                                    <br /><br />
                                                <?php endforeach; ?>
                                                <input type="submit" class="btn btn-default btn-bgcolor-blue" value="Submit">
                                            </div>
                                        </form>
                                    </div>
                                    <div id="panel_tab2_example4" class="tab-pane">
                                        <form method="post" action="/productCart/upTag">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Tags2</label>
                                                <input name="tag[Tag2]" type="text" class="form-control tags-rightinput" placeholder="Vintage, cotton, summer" value="">
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Tags3</label>
                                                <input name="tag[Tag3]" type="text" class="form-control tags-rightinput" placeholder="Vintage, cotton, summer" value="">
                                            </div>
                                            <div class="dg-country-choose">
                                                <?php foreach ($language as $key => $value): ?>
                                                    <input type="checkbox" class="select-all" data-lang="<?php echo $key ?>"/> <?php echo $value ?> <br/>
                                                    <?php foreach ($country[$key] as $country_code): ?>
                                                        <input type="checkbox" name="lang-<?php echo $key ?>[]" value='<?php echo $country_code ?>'/> <img src="img/flag/<?php echo $country_code ?>.png">
                                                    <?php endforeach; ?>
                                                    <br /><br />
                                                <?php endforeach; ?>
                                                <input type="submit" class="btn btn-default btn-bgcolor-blue" value="Submit">
                                            </div>
                                        </form>
                                    </div>
                                    <div id="panel_tab2_example5" class="tab-pane">
                                        <form method="post" action="/productCart/upStatus">
                                            <select name="status" id="dg-product-status-add">
                                                <option value="1">In Stock</option>
                                                <option value="2">Hidden</option>
                                                <option value="3">Out of Stock</option>
                                            </select>
                                            <div class="dg-country-choose">
                                                <?php foreach ($language as $key => $value): ?>
                                                    <input type="checkbox" class="select-all" data-lang="<?php echo $key ?>"/> <?php echo $value ?> <br/>
                                                    <?php foreach ($country[$key] as $country_code): ?>
                                                        <input type="checkbox" name="lang-<?php echo $key ?>[]" value='<?php echo $country_code ?>'/> <img src="img/flag/<?php echo $country_code ?>.png">
                                                    <?php endforeach; ?>
                                                    <br /><br />
                                                <?php endforeach; ?>
                                                <input type="submit" class="btn btn-default btn-bgcolor-blue" value="Submit">
                                            </div>
                                        </form>
                                    </div>
                                    <div id="panel_tab2_example6" class="tab-pane">
                                        <form method="post" action="/productCart/upRelativePro">
                                            <select class="js-example-basic-multiple1" multiple="multiple">
                                            <?php
                                            if (!empty($sku_list)) {
                                                foreach ($sku_list as $sku) {
                                                    echo '<option value="' . $sku . '">' . $sku . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                        <input name="relativeproduct" type="hidden" id="hiddenselectval1" />
                                            <div class="dg-country-choose">
                                                <?php foreach ($language as $key => $value): ?>
                                                    <input type="checkbox" class="select-all" data-lang="<?php echo $key ?>"/> <?php echo $value ?> <br/>
                                                    <?php foreach ($country[$key] as $country_code): ?>
                                                        <input type="checkbox" name="lang-<?php echo $key ?>[]" value='<?php echo $country_code ?>'/> <img src="img/flag/<?php echo $country_code ?>.png">
                                                    <?php endforeach; ?>
                                                    <br /><br />
                                                <?php endforeach; ?>
                                                <input type="submit" class="btn btn-default btn-bgcolor-blue" value="Submit">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
        <script src="js/select2.min.js"></script>
        <script src="js/jquery.icheck.min.js"></script>
        <script src="js/jquery.dataTables.min.js"></script>
        <script src="js/jquery.multiple.select.js"></script>
        <!--左侧nav-->
        <script src="js/jquery.nicescroll.min.js"></script>
        <script src="js/unicorn.js"></script>
        <script src="js/jquery.tagsinput.js"></script>
<?php echo $foot ?>
        <script>
            $(function () {
                $('body').on('click', ".dg-condition-close", function () {
                    $(this).parent().remove();
                })

                $('.modal-producttype').click(function () {
                    var newtab = '<div class="btn-group dg-product-condition"><button class="btn btn-default"><i class="fa fa-th-large"></i> Product Type = ' + $('#dg-product-type-add').find("option:selected").text() + '</button><button class="btn btn-default dg-condition-close"><i class="fa fa-close"></i></button></div>';
                    $('.dg-product-condition-container').append(newtab);
                    $('#producttypemodal').modal('hide');
                })

                $('select').select2();
                $('.tags-rightinput').tagsInput({height: '46px'});
                $(".js-example-basic-multiple1").change(function () {
                    var hiddenselectval1 = $(this).val();
                    $("#hiddenselectval1").val(hiddenselectval1);
                });
                $(".js-example-basic-multiple").change(function () {
                    var hiddenselectval = $(this).val();
                    $("#hiddenselectval").val(hiddenselectval);
                });
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
                $('.tag').tagsInput({height: '38px'});
            });
        </script>
    </body>
</html>