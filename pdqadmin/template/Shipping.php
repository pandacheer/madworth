<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo $template ?>">
        <title>DeliveryMethod</title>
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
                        <h4 class="modal-title" id="myLargeModalLabel">Delivery Method</h4>
                    </div>
                    <div class="modal-body row">
                        <form id="shippingForm">
                            <input type="hidden" name="id" id="id">
                            <input type="hidden" name="country_code" id="country_code">
                            <div class="form-group col-lg-12 col-ms-12 col-xs-12">
                                <label for="name">Shipping rate name</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>


                            <script src="js/jquery.min.js"></script>
                            <script>
                                $(function () {
                                    $("#type").on('change', function () {
                                        //$('#weight-price-rang').hide();
                                        $val = $(this).val();
                                        switch ($val) {
                                            case '1':
                                                $('#weight-price-rang label').text('Weight rang（kg）');
                                                $('#weight-price-rang input').eq(0).val('0.0');
                                                $('#weight-price-rang input').eq(1).val('25.0');
                                                break;
                                            case '2':
                                                $('#weight-price-rang label').text('Price rang');
                                                $('#weight-price-rang input').eq(0).val('50.00');
                                                $('#weight-price-rang input').eq(1).val('100.00');
                                                break;
                                        }
                                        ;
                                    });
                                });
                            </script>
                            <div class="form-group col-lg-12 col-ms-12 col-xs-12 nopadding Criteria">
                                <div class="col-lg-5">
                                    <label for="Criteria">Criteria</label>
                                    <select class="form-control" name="type" id="type">
                                        <option value="1">Based on order weight</option>
                                        <option value="2">Based on order price</option>
                                    </select>
                                </div>
                                <div class="col-lg-1 text-center">
                                    <i class="fa fa-long-arrow-right"></i>
                                </div>
                                <div class="col-lg-6" id="weight-price-rang">
                                    <label>Weight rang（kg）</label>
                                    <div class="row col-lg-12 col-ms-12 col-xs-12 nopadding">
                                        <div class="col-lg-5 nopadding">
                                            <input type="text" class="form-control" placeholder="$" value="0.0" id="min" name="min" />
                                        </div>
                                        <div class="col-md-2 text-center"><i class="fa fa-minus"></i></div>
                                        <div class="col-lg-5 nopadding">
                                            <input type="text" class="form-control" placeholder="$" value="25.0" id="max" name="max" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-6 col-ms-6 col-xs-12 ">
                                <label for="price">Shipping price</label>
                                <input type="text" class="form-control" id="price" placeholder="$0.00" name="price" />
                            </div>
                            <div class="form-group col-lg-6 col-ms-6 col-xs-12 ">
                                <label for="price">Product Page Description</label>
                                <input type="text" class="form-control" id="title" placeholder="Delivert Within Approx 3-4 weeks." name="title" />
                            </div>
                            <div class="form-group col-lg-6 col-ms-6 col-xs-12 ">
                                <label for="price">Estimated time</label>
                                <input type="text" class="form-control" id="estimated_time" placeholder="0" name="estimated_time" />
                            </div>
                            <div class="form-group col-lg-12 col-ms-12 col-xs-12 ">
                                <label>Displayed on the Product Page.</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="showType" id="optionsStandard" value="0" >
                                        Do not Displayed.
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="showType" id="optionsExpress" value="1" >
                                        Express Shipping
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="showType" id="optionsStandard" value="2" >
                                        Standard Shipping
                                    </label>
                                </div>
                            </div>
                        </form>
                        <div class="row col-lg-12 col-ms-12 col-xs-12 text-right">
                            <button class="btn btn-ms btn-default" data-dismiss="modal">Close</button>
                            <button class="btn btn-ms btn-info" id="shippingSave">Save Change</button>
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
                            <h1><i class="glyphicon glyphicon-plane" aria-hidden="true"></i>Shipping Methods</h1>
                        </div>	

                    </div>

                </div>

                <div class="row method-list">
                    <div class="dg-main-account-content col-xs-12 col-sm-12 col-lg-12">
                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">


                            <?php foreach ($shippingArr as $shippings) : ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="heading<?php echo $shippings['_id'] ?>">
                                        <a class="collapsed dg-main-account-content-order-title" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $shippings['_id'] ?>" aria-expanded="false" aria-controls="collapse<?php echo $shippings['_id'] ?>">
                                            <h3 class="panel-title">
                                                <img src="img/flag/<?php echo $shippings['_id'] ?>.png" /> <?php echo $countryArr[$shippings['_id']] ?>
                                                <span class="glyphicon glyphicon-chevron-down pull-right" aria-hidden="true"></span>
                                            </h3>
                                        </a>
                                    </div>
                                    <div id="collapse<?php echo $shippings['_id'] ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $shippings['_id'] ?>">
                                        <div class="panel-body">
                                            <table class="table table-hover Customerslist-table">
                                                <tr>
                                                    <th>Methods</th>
                                                    <th>Condition</th>
                                                    <th>Postage</th>
                                                    <th>Edit</th>
                                                    <th>Delete</th>
                                                </tr>
                                                <tbody>
                                                    <?php foreach ($shippings['model'] as $model) : ?>
                                                        <tr>
                                                            <td><span class="button" data-toggle="modal" data-target=".bs-example-modal-lg"><?php echo $model['name'] ?></span></td>
                                                            <td><?php echo $model['type'] == 1 ? number_format($model['min'] / 100, 2) . 'kg — ' . number_format($model['max'] / 100, 2) . 'kg' : '$' . number_format($model['min'] / 100, 2) . ' — $' . number_format($model['max'] / 100, 2) ?></td>
                                                            <td>$<?php echo number_format($model['price'] / 100, 2) ?></td>
                                                            <td>
                                                                <button class="btn btn-default btn-sm" data-toggle="modal" data-target=".bs-example-modal-lg" data-country="<?php echo $shippings['_id'] ?>" data-id="<?php echo $model['id'] ?>" data-name="<?php echo $model['name'] ?>" data-type="<?php echo $model['type'] ?>" data-price="<?php echo number_format($model['price'] / 100, 2) ?>" data-min="<?php echo number_format($model['min'] / 100, 2) ?>" data-max="<?php echo number_format($model['max'] / 100, 2) ?>" data-title="<?php echo $model['title'] ?>" data-estimated_time="<?php echo $model['estimated_time'] ?>"  data-showType="<?php echo $model['showType'] ?>" ><i class="fa fa-pencil"></i></button>
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-default btn-sm" data-country="<?php echo $shippings['_id'] ?>" data-id="<?php echo $model['id'] ?>"><i class="fa fa-trash-o"></i></button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>

                                                </tbody>
                                            </table>
                                            <div>
                                                <button type="button" class="btn btn-default addDeliveryMethod" data-toggle="modal" data-target=".bs-example-modal-lg" data-country="<?php echo $shippings['_id'] ?>" data-id="0" data-showtype="0"><i class="fa fa-plus"></i> Add Delivery Method</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>



                        </div>                
                    </div>
                    <div class="clearfix"></div>
                </div>	
            </div>
            <div class="row">
                <div id="footer" class="col-xs-12"></div>
            </div>
        </div>

        <!-- Large modal -->


        <script src="js/jquery-ui.custom.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <!--左侧nav-->
        <script src="js/jquery.nicescroll.min.js"></script>
        <script src="js/unicorn.js"></script>
        <?php echo $foot ?>

        <script>
                                $(function () {
                                    var buttonshowhidebtn;
                                    $(".fa-trash-o").parents('button').on('click', function () {
                                        $val = confirm('Are you sure you want to delete?');
                                        if ($val === true) {
                                            var that = this;
                                            $.post('<?php echo site_url('shipping/delete') ?>', {
                                                country_code: $(that).data('country'),
                                                id: $(that).data('id')
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
                                        $("#country_code").val(buttonshowhidebtn.attr("data-country"));
                                        $("#name").val(buttonshowhidebtn.attr("data-name"));
                                        $("#id").val(buttonshowhidebtn.attr("data-id"));
                                        $("#min").val(buttonshowhidebtn.attr("data-min"));
                                        $("#max").val(buttonshowhidebtn.attr("data-max"));
                                        $("#price").val(buttonshowhidebtn.attr("data-price"));
                                        $("#type").val(buttonshowhidebtn.attr("data-type"));
                                        if (parseInt($('#type').val()) === 1) {
                                            $('#weight-price-rang label').text('Weight rang（kg）');
                                        } else {
                                            $('#weight-price-rang label').text('Price rang');
                                        }
                                        $("#title").val(buttonshowhidebtn.attr("data-title"));
                                        $("#estimated_time").val(buttonshowhidebtn.attr("data-estimated_time"));
                                        $("input[name='showType']").prop("checked",false);
                                        $("input[name='showType'][value="+buttonshowhidebtn.attr("data-showtype")+"]").prop("checked",true);
                                        
                                    });

                                    $("#shippingSave").on("click", function () {
                                        $.ajax({
                                            type: "POST",
                                            url: "<?php echo site_url('shipping/save') ?>",
                                            dataType: 'json',
                                            data: $("#shippingForm").serialize(),
                                            success: function (result) {
                                                if (result.success) {
                                                    $('.bs-example-modal-lg').modal('hide');
                                                    window.location.reload();
                                                } else {
                                                    alert(result.error);
                                                }

                                            }
                                        });
                                    });
                                });
        </script>
    </body>
</html>
