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
                <form method="post" action="<?php echo site_url('category/insert') ?>">
                    <div id="content-header" class="mini">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-lg-6">
                                <h1>Product Type</h1>
                            </div>	
                            <div class="col-xs-12 col-sm-6 col-lg-6">
                                <div class="pull-right">
                                    <a href="/category"><button type="button" class="btn btn-default btn-bgcolor-white">Cancel</button></a>
                                    <button type="submit" class="btn btn-default btn-bgcolor-blue">Save Product Type</button>
                                </div>	
                            </div>	
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-xs-12 col-sm-12 col-lg-12">
                            <div class="widget-box widget-box-hledit widget-box-hledit-productadd-left">

                                    <div class="form-group">
                                        <label for="en">Name</label>
                                        <input type="text" class="form-control" name="title" placeholder="Product Type">
                                    </div>

                            </div>	

                        </div>
<!--                        <div class="col-xs-12 col-sm-4 col-lg-4">
                            <div class="widget-box widget-box-hledit">
                                <div class="widget-title">
                                    <h4>Country</h4>
                                </div>
                                <div class="widget-content nopadding">

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
                            </div>
                        </div>-->


                    </div>
                </form>
            </div>
            <div class="row">
                <div id="footer" class="col-xs-12"></div>
            </div>
        </div>

        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery-ui.custom.js"></script>
        <script src="js/jquery.icheck.min.js"></script>
        <script src="js/select2.min.js"></script>
        <script src="js/fileinput.min.js"></script>
        <script src="js/sortable.min.js"></script>
        <!--左侧nav-->
        <script src="js/jquery.nicescroll.min.js"></script>
        <script src="js/unicorn.js"></script>

        <!--文本编辑器-->
        <script src="js/summernote.js"></script>
        <?php echo $foot ?>

        <script type="text/javascript">

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
