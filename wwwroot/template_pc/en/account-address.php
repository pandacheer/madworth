<?php echo $head; ?>
<div class="dg-main">
    <div class="container">
        <div class="row">
            <div class="col-xs-10 col-xs-12">
                <div class="dg-main-account clearfix">
                    <div class="dg-main-account-hander clearfix">
                        <div class="dg-main-account-hander-title">My Account</div>
                        <!-- <div class="dg-main-account-hander-balance"><b>Store Credit : </b>$0.00</div> -->
                    </div>
                    <div class="dg-main-account-menu">
                        <a class="dg-main-account-menu-tab dg-main-account-menu-tab-detail" href="/personal"><div class="icon"></div><span class="text">Personal Details</span></a>


                        <a class="dg-main-account-menu-tab dg-main-account-menu-tab-orders " href="/personal/order"><div class="icon"></div><span class="text">My Orders</span></a>
                        <a class="dg-main-account-menu-tab dg-main-account-menu-tab-coupon " href="/personal/coupon"><div class="icon"></div><span class="text">My Coupons</span></a>

                        <a class="dg-main-account-menu-tab dg-main-account-menu-tab-address active"><div class="icon"></div><span class="text">Address</span></a>

                        <a class="dg-main-account-menu-tab dg-main-account-menu-tab-info " href="/pages/faq"><div class="icon"></div><span class="text">Need Some Help?</span></a>
                    </div>
                    <div class="dg-main-account-content dg-main-account-content-address">
                        <div class="shipping">
                            <h4>My Shipping Address<button class="btn btn-default pull-right" id="manage_billing">Manage Billing Address</button></h4>
                            <?php if ($listAddress): ?>
                                <?php foreach ($listAddress as $key => $address) : ?>
                                    <?php if ($key == 0): ?>

                                        <div class="panel dg-blue-panel">
                                            <div class="panel-heading">
                                                <h3 class="panel-title" style="text-transform: capitalize">
                                                    <?php echo $address['receive_firstName'] . ' ' . $address['receive_lastName'] ?> - Primary Address
                                                    <span class="pull-right clickable" data-toggle="modal" data-target="#address" data-bind="<?php echo $address['receive_id'] ?>" id="edit">Edit Address</span>
                                                </h3>
                                            </div>

                                            <div class="panel-body">
                                                <span style="text-transform: capitalize"><?php echo $address['receive_firstName'] . ' ' . $address['receive_lastName'] ?></span><br>
                                                <?php if ($address['receive_add2']): ?>	 
                                                    <?php echo $address['receive_add2'] ?> / <?php echo $address['receive_add1'] ?><br>
                                                <?php else: ?>
                                                    <?php echo $address['receive_add1'] ?><br/>
                                                <?php endif; ?>
                                                <?php echo $address['receive_city'] ?> , <?php echo $address['receive_province'] ?> , <?php echo $address['receive_zipcode'] ?><br>
                                                <?php echo $address['receive_phone'] ?><br>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title" style="text-transform: capitalize">
                                                    <?php echo $address['receive_firstName'] . ' ' . $address['receive_lastName'] ?>
                                                    <span class="pull-right clickable dg-blue"  data-toggle="modal" data-target="#address" data-bind="<?php echo $address['receive_id'] ?>" id="edit1">Edit Address</span>
                                                </h3>
                                            </div>

                                            <div class="panel-body">
                                                <span style="text-transform: capitalize"><?php echo $address['receive_firstName'] . ' ' . $address['receive_lastName'] ?></span><br>
                                                <?php if ($address['receive_add2']): ?>	 
                                                    <?php echo $address['receive_add2'] ?> / <?php echo $address['receive_add1'] ?><br>
                                                <?php else: ?>
                                                    <?php echo $address['receive_add1'] ?><br/>
                                                <?php endif; ?>
                                                <?php echo $address['receive_city'] ?> , <?php echo $address['receive_province'] ?> , <?php echo $address['receive_zipcode'] ?><br>
                                                <?php echo $address['receive_phone'] ?><br>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="dg-main-thankyou">
                                    <div class="dg-main-reset-ticker">
                                        <i class="fa fa-map-o"></i>
                                        <div class="dg-main-thankyou-ticker-thanktitle">You currently don't have any shipping addresses.</div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="text-center">
                                <button type="button" class="btn btn-lg btn-success " data-toggle="modal" data-target="#address" data-count="<?php echo $count; ?>" data-bind="0" id="btnAdd"><i class="glyphicon glyphicon-plus"></i> Add a New Shipping Address</button>
                            </div>

                            <div class="modal fade modal-address" id="address" tabindex="-1" role="dialog" aria-labelledby="address">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <span class="modal-title" style="font-size: 20px;">New Address</span>
                                        </div>

                                        <div class="modal-body">
                                            <form id="addressForm">
                                                <input type="hidden" name="receive_id" id="receive_id" value="0">
                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="firstname">First Name<span class="reddian">*</span></label>
                                                            <input type="text" class="form-control dg-requiredfield" id="firstname" name="firstname">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="lastname">Last Name<span class="reddian">*</span></label>
                                                            <input type="text" class="form-control dg-requiredfield" id="lastname" name="lastname">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="tel">Phone</label>
                                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="optional">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="form-group">
                                                            <label for="address">Address<span class="reddian">*</span></label>
                                                            <input type="text" class="form-control dg-requiredfield" id="address1" name="address1">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="apt">Apt,Suite,etc</label>
                                                            <input type="text" class="form-control" id="apt" name="apt" placeholder="optional">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="form-group">
                                                            <label for="surburb"><?php echo $addCountry['city'] ?><span class="reddian">*</span></label>
                                                            <input type="text" class="form-control dg-requiredfield" id="suburb" name="suburb"  />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="postcode"><?php echo $addCountry['zipcode'] ?><span class="reddian">*</span></label>
                                                            <input type="text" class="form-control dg-requiredfield" id="postcode" name="postcode" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="form-group">
                                                            <label for="state"><?php echo $addCountry['state'] ?><span class="reddian">*</span></label>
                                                            <select class="form-control dg-main-cart-payment-lr-left-select dg-requiredfield state" name="state"  id="state">
                                                                <?php if (count($States) == 0): ?>
                                                                    <option value="<?php echo $countryList[$country]['name'] ?>"><?php echo $countryList[$country]['name'] ?></option>
                                                                <?php else: ?>
                                                                    <option>Please select your <?php echo $addCountry['state'] ?></option>
                                                                    <?php foreach ($States as $StateCode => $StateName) : ?>
                                                                        <option value="<?php echo $StateName ?>"><?php echo $StateName ?></option>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="country">COUNTRY<span class="reddian">*</span></label>
                                                            <input type="text" class="form-control dg-requiredfield" id="country" name="country" value="<?php echo $countryList[$country]['name'] ?>" readonly >
    <!--                                                        <select class="form-control dg-main-cart-payment-lr-left-select" id="country" name="country">
                                                                <option selected="selected" Readonly="Readonly">Austrlia</option>
                                                            </select>-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" id="btnDelete" style="float: left;">Delete</button>
                                            <button type="button" class="btn btn-default" style="float: left;" id="btnPrimary">Set as the Primary Address</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal" id="close">Close</button>
                                            <button type="button" class="btn btn-success submit" id="btnSave" >Save</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>




                        <!-- billing address -->
                        <div class="billing" style="display: none">
                            <h4>My Billing Address<button class="btn btn-default pull-right" id="manage_shipping">Manage Shipping Address</button></h4>
                            <?php if ($billAddress): ?>
                                <?php foreach ($billAddress as $key => $bill_address) : ?>
                                    <?php if ($key == 0): ?>
                                        <div class="panel dg-blue-panel">
                                            <div class="panel-heading">
                                                <h3 class="panel-title" style="text-transform: capitalize">
                                                    <?php echo $bill_address['receive_firstName'] . ' ' . $bill_address['receive_lastName'] ?> - Primary Address
                                                    <span class="pull-right clickable" data-toggle="modal" data-target="#billaddress" data-bind-bill="<?php echo $bill_address['receive_id'] ?>" id="billedit">Edit Address</span>
                                                </h3>
                                            </div>

                                            <div class="panel-body">
                                                <span style="text-transform: capitalize"><?php echo $bill_address['receive_firstName'] . ' ' . $bill_address['receive_lastName'] ?></span><br>
                                                <?php if ($bill_address['receive_add2']): ?>	 
                                                    <?php echo $bill_address['receive_add2'] ?> / <?php echo $bill_address['receive_add1'] ?><br>
                                                <?php else: ?>
                                                    <?php echo $bill_address['receive_add1'] ?><br/>
                                                <?php endif; ?>
                                                <?php echo $bill_address['receive_city'] ?> , <?php echo $bill_address['receive_province'] ?> , <?php echo $bill_address['receive_zipcode'] ?><br>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title" style="text-transform: capitalize">
                                                    <?php echo $bill_address['receive_firstName'] . ' ' . $bill_address['receive_lastName'] ?>
                                                    <span class="pull-right clickable dg-blue"  data-toggle="modal" data-target="#billaddress" data-bind-bill="<?php echo $bill_address['receive_id'] ?>" id="billedit1">Edit Address</span>
                                                </h3>
                                            </div>

                                            <div class="panel-body">
                                                <span style="text-transform: capitalize"><?php echo $bill_address['receive_firstName'] . ' ' . $bill_address['receive_lastName'] ?></span><br>
                                                <?php if ($bill_address['receive_add2']): ?>	 
                                                    <?php echo $bill_address['receive_add2'] ?> / <?php echo $bill_address['receive_add1'] ?><br>
                                                <?php else: ?>
                                                    <?php echo $bill_address['receive_add1'] ?><br/>
                                                <?php endif; ?>
                                                <?php echo $bill_address['receive_city'] ?> , <?php echo $bill_address['receive_province'] ?> , <?php echo $bill_address['receive_zipcode'] ?><br>
                                            </div>
                                        </div>

                                    <?php endif; ?>
                                    <div id="billappend_address"></div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="dg-main-thankyou">
                                    <div class="dg-main-reset-ticker">
                                        <i class="fa fa-map-o"></i>
                                        <div class="dg-main-thankyou-ticker-thanktitle">You currently don't have any billing addresses.</div>
                                    </div>
                                </div>
                            <?php endif; ?>


                            <div class="text-center">
                                <button type="button" class="btn btn-lg btn-success " data-toggle="modal" data-target="#billaddress" data-count="<?php echo $billCount; ?>" data-bind-bill="0" id="billbtnAdd"><i class="glyphicon glyphicon-plus"></i> Add a New Billing Address</button>
                            </div>



                            <div class="modal fade modal-billaddress" id="billaddress" tabindex="-1" role="dialog" aria-labelledby="address">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <span class="modal-title-bill" style="font-size: 20px;">New Address</span>
                                        </div>

                                        <div class="modal-body">
                                            <form id="billaddressForm">
                                                <input type="hidden" name="billreceive_id" id="billreceive_id" value="0">
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <div class="form-group">
                                                            <label for="firstname">First Name<span class="reddian">*</span></label>
                                                            <input type="text" class="form-control dg-requiredfield1" id="billfirstname" name="bill_firstname">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="form-group">
                                                            <label for="lastname">Last Name<span class="reddian">*</span></label>
                                                            <input type="text" class="form-control dg-requiredfield1" id="billlastname" name="bill_lastname">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="form-group">
                                                            <label for="address">Address<span class="reddian">*</span></label>
                                                            <input type="text" class="form-control dg-requiredfield1" id="billaddress1" name="bill_address">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="apt">Apt,Suite,etc</label>
                                                            <input type="text" class="form-control" id="billapt" name="bill_apt" placeholder="optional">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="form-group">
                                                            <label for="surburb"><?php echo $addCountry['city'] ?><span class="reddian">*</span></label>
                                                            <input type="text" class="form-control dg-requiredfield1" id="billsuburb" name="bill_suburb">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="postcode"><?php echo $addCountry['zipcode'] ?><span class="reddian">*</span></label>
                                                            <input type="text" class="form-control dg-requiredfield1" id="billpostcode" name="bill_postcode" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-8">
                                                        <div class="form-group">
                                                            <label for="state"><?php echo $addCountry['state'] ?><span class="reddian">*</span></label>
                                                            <select class="form-control dg-main-cart-payment-lr-left-select dg-requiredfield1 bill_state" name="bill_state"  id="billstate">
                                                                <?php if (count($States) == 0): ?>
                                                                    <option value="<?php echo $countryList[$country]['name'] ?>"><?php echo $countryList[$country]['name'] ?></option>
                                                                <?php else: ?>
                                                                    <option>Please select your <?php echo $addCountry['state'] ?></option>
                                                                    <?php foreach ($States as $StateCode => $StateName) : ?>
                                                                        <option value="<?php echo $StateName ?>"><?php echo $StateName ?></option>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <div class="form-group">
                                                            <label for="country">COUNTRY<span class="reddian">*</span></label>
                                                            <input type="text" class="form-control dg-requiredfield1" id="billcountry" name="bill_country" value="<?php echo $countryList[$country]['name'] ?>" readonly >
    <!--                                                        <select class="form-control dg-main-cart-payment-lr-left-select" id="country" name="country">
                                                                <option selected="selected" Readonly="Readonly">Austrlia</option>
                                                            </select>-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" id="billbtnDelete" style="float: left;">Delete</button>
                                            <button type="button" class="btn btn-default" style="float: left;" id="billbtnPrimary">Set as the Primary Address</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal" id="billclose">Close</button>
                                            <button type="button" class="btn btn-success submit" id="billbtnSave" >Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php echo $shoppingcart ?>

        </div>
    </div>
</div>  

<?php echo $foot ?>

<script>
    $(function () {
        $('#manage_billing').click(function () {
            $('.shipping').hide();
            $('.billing').show();
        })
        $('#manage_shipping').click(function () {
            $('.billing').hide();
            $('.shipping').show();
        })
    })
    $(function () {
        var buttonshowhidebtn;
        $('.modal-address').on('show.bs.modal', function (event) {
            buttonshowhidebtn = $(event.relatedTarget);
            var receive_id = buttonshowhidebtn.attr("data-bind");
            $('#receive_id').val(receive_id);
            if (parseInt(receive_id) > 0) {
                $('.modal-title').html('Edit Address');
                $.post('/personal/getAddressInfo', {
                    receive_id: receive_id
                }, function (result) {
                    if (result.success) {
                        $('#firstname').val(result.receive_firstName);
                        $('#lastname').val(result.receive_lastName);
                        $('#phone').val(result.receive_phone);
                        $('#address1').val(result.receive_add1);
                        $('#apt').val(result.receive_add2);
                        $('#suburb').val(result.receive_city);
                        $('#postcode').val(result.receive_zipcode);
                        $('#state').val(result.receive_province);
                        $('#country').val(result.receive_country);
                    } else {
                        alert(result.error);
                        return false;
                    }
                }, 'json');
            } else {
                $('.modal-title').html('New Address');
                $('#firstname').val('');
                $('#lastname').val('');
                $('#phone').val('');
                $('#address1').val('');
                $('#apt').val('');
                $('#suburb').val('<?php echo (count($States)==0)?$countryList[$country]['name']:"" ?>');
                $('#postcode').val('');
            }
        });



        $('.modal-billaddress').on('show.bs.modal', function (event) {
            buttonshowhidebtn = $(event.relatedTarget);
            var receive_id = buttonshowhidebtn.attr("data-bind-bill");
            $('#billreceive_id').val(receive_id);
            if (parseInt(receive_id) > 0) {
                $('.modal-title-bill').html('Edit Address');
                $.post('/personal/getBillAddressInfo', {
                    billreceive_id: receive_id
                }, function (result) {
                    if (result.success) {
                        $('#billfirstname').val(result.receive_firstName);
                        $('#billlastname').val(result.receive_lastName);
                        $('#billaddress1').val(result.receive_add1);
                        $('#billapt').val(result.receive_add2);
                        $('#billsuburb').val(result.receive_city);
                        $('#billpostcode').val(result.receive_zipcode);
                        $('#billstate').val(result.receive_province);
                        $('#billcountry').val(result.receive_country);
                    } else {
                        alert(result.error);
                        return false;
                    }
                }, 'json');
            } else {
                $('.modal-title-bill').html('New Address');
                $('#billfirstname').val('');
                $('#billlastname').val('');
                $('#billaddress1').val('');
                $('#billapt').val('');
                $('#billsuburb').val('<?php echo (count($States)==0)?$countryList[$country]['name']:"" ?>');
                $('#billpostcode').val('');
            }
        });
    });

    $(function () {
        $(".dg-requiredfield").each(function () {
            $(this).bind('focus', function () {
                $(this).css('border-color', '#00B6C6');
            });
            $(this).bind('blur', function () {
                $(this).css('border-color', '#ccc');
            });
        })

    })
    $(function () {
        $(".dg-requiredfield1").each(function () {
            $(this).bind('focus', function () {
                $(this).css('border-color', '#00B6C6');
            });
            $(this).bind('blur', function () {
                $(this).css('border-color', '#ccc');
            });
        })

    })



    $("#btnSave").click(function () {
        ifValid = true;

        $(".dg-requiredfield").each(function () {
            if (!$(this).val()) {
                ifValid = false;
                $(this).css('border-color', '#dd514c');
            }
        });
        if ($('.state').val() == "Please select your <?php echo $addCountry['state'] ?>") {
            $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in all mandatory fields marked *', position: "bottom"});
            $('.state').css('border-color', '#dd514c');
            ifValid = false;
        }
        if (!ifValid) {
            $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in all mandatory fields marked *', position: "bottom"});
        }
        if (ifValid) {
            button_addcart_disabled(this);
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('personal/addressInsert') ?>",
                dataType: 'json',
                data: $("#addressForm").serialize(),
                success: function (result) {
                    if (result) {
                        button_addcart_enabled(this);
                        location.reload();
                    }
                }
            });
        }
    });



    $("#billbtnSave").click(function () {
        ifValid = true;

        $(".dg-requiredfield1").each(function () {
            if (!$(this).val()) {
                ifValid = false;
                $(this).css('border-color', '#dd514c');
            }
        });
        if ($('.bill_state').val() == "Please select your <?php echo $addCountry['state'] ?>") {
            $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in all mandatory fields marked *', position: "bottom"});
            $('.bill_state').css('border-color', '#dd514c');
            ifValid = false;
        }
        if (!ifValid) {
            $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in all mandatory fields marked *', position: "bottom"});
        }
        if (ifValid) {
            button_addcart_disabled(this);
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('personal/billAddressInsert') ?>",
                dataType: 'json',
                data: $("#billaddressForm").serialize(),
                success: function (result) {
                    if (result) {
                        button_addcart_enabled(this);
                        location.reload();
                        /*$row='<div class="panel panel-default">'+
                         '<div class="panel-heading">'+
                         '<h3 class="panel-title" style="text-transform: capitalize">'+
                         result.receive_firstName + '&nbsp' + result.receive_lastName +
                         '<span class="pull-right clickable dg-blue"  data-toggle="modal" data-target="#billaddress" data-bind-bill="'+result.receive_id+'" id="billedit1">Edit Address</span>'+
                         '</h3>'+
                         '</div>'+
                         '<div class="panel-body">'+
                         '<span style="text-transform: capitalize">'+result.receive_firstName + '&nbsp' + result.receive_lastName +'</span><br>'+
                         result.receive_add2 + '&nbsp' + result.receive_add1 + '<br>' +
                         result.receive_city + '&nbsp' + result.receive_province + '&nbsp' + result.receive_zipcode + '<br>' +
                         result.receive_country + '<br>' +
                         result.receive_phone + '<br>' + 
                         '</div>'+
                         '</div>';
                         $("#billappend_address").append($row);
                         $('#billaddress').hide();*/
                    }
                }
            });
        }

    });



    $('#edit,#edit1').click(function () {
        $('#btnDelete,#btnPrimary').show();
    })
    $('#billedit,#billedit1').click(function () {
        $('#billbtnDelete,#billbtnPrimary').show();
    })
    $('#btnAdd').click(function () {
        if ($('#btnAdd').data('count') >= 5) {
            $.notifyBar({cssClass: "dg-notify-error", html: 'You can add maximum 5 addresses.', position: "bottom"});
            return false;
        } else {
            $('#btnDelete,#btnPrimary').hide();
        }

    })
    $('#billbtnAdd').click(function () {
        if ($('#billbtnAdd').data('count') >= 5) {
            $.notifyBar({cssClass: "dg-notify-error", html: 'You can add maximum 5 addresses.', position: "bottom"});
            return false;
        } else {
            $('#billbtnDelete,#billbtnPrimary').hide();
        }

    })

    $("#btnDelete").click(function () {
        button_buynow_disabled(this);
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('personal/addressDelete') ?>",
            dataType: 'json',
            data: $("#addressForm").serialize(),
            success: function (result) {
                if (result) {
                    location.reload();
                }
            }
        });
    });



    $("#billbtnDelete").click(function () {
        button_buynow_disabled(this);
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('personal/addressBillDelete') ?>",
            dataType: 'json',
            data: $("#billaddressForm").serialize(),
            success: function (result) {
                if (result) {
                    location.reload();
                }
            }
        });
    });





    $("#btnPrimary").click(function () {
        button_buynow_disabled(this);
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('personal/addressDefault') ?>",
            dataType: 'json',
            data: $("#addressForm").serialize(),
            success: function (result) {
                if (result) {
                    location.reload();
                }
            }
        });
    });



    $("#billbtnPrimary").click(function () {
        button_buynow_disabled(this);
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('personal/billAddressDefault') ?>",
            dataType: 'json',
            data: $("#billaddressForm").serialize(),
            success: function (result) {
                if (result) {
                    location.reload();
                }
            }
        });
    });




    $("#close").click(function () {
        $(".dg-requiredfield").each(function () {
            $(this).css('border-color', '#ccc');
        })
    })
    $("#billclose").click(function () {
        $(".dg-requiredfield1").each(function () {
            $(this).css('border-color', '#ccc');
        })
    })


    cartempty();
</script>
</body>
</html>
