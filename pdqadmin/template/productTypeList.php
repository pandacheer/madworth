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
        <div id="wrapper">
            <?php echo $head; ?>
            <div id="content">
                <div id="content-header" class="mini">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-lg-6">
                            <h1><i class="glyphicon glyphicon-list-alt" aria-hidden="true"></i> Product Type</h1>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-lg-6"><a href="<?php echo site_url('category/loadAddPage') ?>"><button class="btn btn-info pull-right" type="button"><i class="fa fa-plus fa-sm"></i> Add Product Type</button></a></div>
                    </div>	
                </div>
                <div id="breadcrumb">
                    <a href="/category" class="tip-bottom"><i class="fa fa-tags"></i>Products Type</a>
                    <a class="current">Products Type List</a>
                </div>
                <div class="row">

                    <div class="col-xs-12">

                        <div class="row">
                            <div class="widget-box widget-box-hledit widget-box-hledit-order-left">
                                <div class="widget-content nopadding">
                                    <table class="table table-striped table-hover Customerslist-table product_type_list">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>ID</th>
                                                <th>View/Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($docs as $doc): ?>
                                            <tr class="collectionadd-hidebox-inputbox">
                                                    <td><?php echo $doc['title'] ?></td>
                                                    <td><?php echo $doc['_id'] ?></td>
                                                    <td><a href="<?php echo site_url('category/loadEditPage/' . $doc['_id']) ?>" ><button type="button" class="btn btn-default btn-sm product-operation-detect"><i class="fa fa-pencil fa-lg"></i></button></a></td>
                                                    <td><button type="button" class="btn btn-default btn-sm product-operation-detect" data-bind="<?php echo $doc['_id'] ?>"><i class="fa fa-trash-o fa-lg"></i></button></td>
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
        <script src="js/unicorn.jui.js"></script>
        <script src="js/bootstrap-switch.js"></script>
        <?php echo $foot ?>     

        <script >
            $(document).ready(function () {
                $('.fa-trash-o').parents('button').click(function () {
                    if (!$(this).parent().parent().parent().hasClass('collectionadd-hidebox-inputbox-one')) {
                        var r = confirm("Are you sure you want to delete ?")
                        if (r == true) {
                            var id = $(this).data('bind');
                            var $that = $(this);
                            $.post('<?php echo site_url('/category/remove') ?>',{category_id:id},function(data){
                                if(data.status==200){
                                    $that.parent().parent(".collectionadd-hidebox-inputbox").remove();
                                }else{
                                    alert(data.info);
                                }
                            },'json');
                            return false;
                        } else {
                            return false;
                        }
                    }
                });
            });
        </script>
    </body>
</html>
