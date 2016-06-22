<?php echo $head; ?>

<div class="dg-breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <ol class="breadcrumb cartbreadcrumb">
                    <li><a href="/">Home</a></li>
                    <li class="active">shopping cart</li>
                </ol>
            </div>
        </div>
    </div>
</div>




<div class="dg-main dg-main-cart">
    <div class="container">
        <div class="row">


            <div class="col-xs-10" id="full-cart">
                <div class="dg-main-cart-order clearfix">
                    <div class="dg-main-cart-order-left">Complete Your Order</div>
                    <div class="dg-main-cart-order-right">
                        <i class="fa fa-clock-o"></i> Be quick! Stock is not reserved
                        until you place your order
                    </div>
                </div>




                <div class="dg-main-cart-payment">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="dg-main-cart-payment-title">Shipping and Payment</div>
                        </div>
                    </div>
                    <div class="row">

                        <form action="<?php echo site_url('order/checkoutPayment') ?>" method="POST" onsubmit="return check_cart()">
                            <div class="col-xs-6 dg-main-cart-payment-lr">
                                <div class="dg-main-cart-payment-lr-blur">
                                    <div class="panel panel-primary dg-blur-hint-paypal">
                                        <div class="panel-body">
                                            <img src="<?php echo $cdn ?>image/paypal-shipping.png"><br/>
                                            Please Complete Your Shipping Address on PayPal Website 
                                        </div>
                                    </div>
                                </div>
                                <h4 class="dg-main-cart-payment-lr-titleadd"><i class="fa fa-plane"></i> Shipping Address</h4>
                                <div class="dg-main-cart-payment-lr-left">
                                    <!--<form class="dg-main-cart-payment-lr-left-form">-->                  
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label for="firstname">Name<span class="reddian">*</span></label>
                                                <input type="text" class="form-control dg-requiredfield" id="firstname" name="firstname" value="<?php echo $paypalResult['PAYMENTREQUEST_0_SHIPTONAME'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-xs-6">  
                                            <!--                                            <div class="form-group">
                                                                                            <label for="lastname">Last Name<span class="reddian">*</span></label>
                                                                                            <input type="text" class="form-control" id="lastname" value="" readonly>
                                                                                        </div>-->
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label for="email">Email Address<span class="reddian">*</span></label>
                                                <input type="text" class="form-control dg-requiredfield" id="email" value="<?php echo $this->session->userdata('member_email') ? $this->session->userdata('member_email') : $paypalResult['EMAIL'] ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!--                                        <div class="col-xs-8">
                                                                                    <div class="form-group">
                                                                                        <label for="company">Company</label>
                                                                                        <input type="text" class="form-control" id="company" name="company" placeholder="optional" value="">
                                                                                    </div>
                                                                                </div>-->
                                        <div class="col-xs-12">
                                            <div class="form-group">
                                                <label for="tel">Phone</label>
                                                <input type="text" class="form-control" id="phone" name="phone" placeholder="optional" value="<?php echo array_key_exists('PAYMENTREQUEST_0_SHIPTOPHONENUM', $paypalResult) ? $paypalResult['PAYMENTREQUEST_0_SHIPTOPHONENUM'] : '' ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <div class="form-group">
                                                <label for="address">Address<span class="reddian">*</span></label>
                                                <input type="text" class="form-control dg-requiredfield" id="address" name="address" value="<?php echo $paypalResult['PAYMENTREQUEST_0_SHIPTOSTREET'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="form-group">
                                                <label for="apt">Apt,Suite,etc</label>
                                                <input type="text" class="form-control" id="apt" name="apt" value="<?php echo array_key_exists('PAYMENTREQUEST_0_SHIPTOSTREET2', $paypalResult) ? $paypalResult['PAYMENTREQUEST_0_SHIPTOSTREET2'] : '' ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <div class="form-group">
                                                <label for="surburb"><?php echo $addCountry['city'] ?><span class="reddian">*</span></label>
                                                <input type="text" class="form-control dg-requiredfield" id="suburb" name="suburb" value="<?php echo array_key_exists('PAYMENTREQUEST_0_SHIPTOCITY', $paypalResult) ? $paypalResult['PAYMENTREQUEST_0_SHIPTOCITY'] : $countryList[$country]['name'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="form-group">
                                                <label for="postcode"><?php echo $addCountry['zipcode'] ?><span class="reddian">*</span></label>
                                                <input type="tetx" class="form-control dg-requiredfield" id="postcode" name="postcode" value="<?php echo $paypalResult['PAYMENTREQUEST_0_SHIPTOZIP'] ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-8">
                                            <div class="form-group">
                                                <label for="state"><?php echo $addCountry['state'] ?><span class="reddian">*</span></label>
                                                <input type="text" class="form-control dg-requiredfield" id="state" name="state" value="<?php echo array_key_exists('PAYMENTREQUEST_0_SHIPTOSTATE', $paypalResult) ? $paypalResult['PAYMENTREQUEST_0_SHIPTOSTATE'] : $countryList[$country]['name'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div class="form-group">
                                                <label for="country">COUNTRY<span class="reddian">*</span></label>
                                                <input type="tetx" class="form-control" id="country" name="country" value="<?php echo $countryList[$country]['name'] //$paypalResult['PAYMENTREQUEST_0_SHIPTOCOUNTRYNAME']            ?>" readonly>

                                            </div>
                                        </div>
                                    </div>
                                    <!--</form>-->

                                </div>
                            </div>
                            <div class="col-xs-6 dg-main-cart-payment-lr">
                                <h4 class=" dg-main-cart-payconfirm-lr-titleme">Choose a Payment Method</h4>
                                <div class="dg-main-cart-payment-lr-right">
                                    <!--<form class="dg-main-cart-payment-lr-right-form" action="<?php // echo site_url('order/checkoutPayment')            ?>" method="POST">-->
                                    <div class="dg-main-cart-payment-lr-right-form-msg">
                                        <img src="<?php echo $cdn ?>image/lock.png" style="display:inline-block; float:left;">
                                        <p class="dg-main-cart-payment-lr-right-form-msg-p">Secure Credit Card Payment </p>
                                        <h6 class="dg-main-cart-payment-lr-right-form-msg-h5">All payments are encrypted with SSL security</h6>
                                    </div>
                                    <div class="dg-main-cart-payment-lr-right-form-method">
                                        <div class="row">

                                            <div class="dg-main-cart-payment-lr-right-form-method-list" id="hidelist">
                                                <label>
                                                    <div class="col-xs-8 dg-main-cart-payment-lr-right-form-method-list-title">
                                                        <input type="radio" class="dg-main-cart-payment-lr-right-form-method-list-title-input" name="method" value="paypal">
                                                        <small class="dg-main-cart-public-stylecss">Paypal</small>
                                                    </div>
                                                    <div class="col-xs-4 dg-main-cart-payment-lr-right-form-method-list-img"><img src="<?php echo $cdn ?>image/paypal.png"></div>
                                                </label>
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="dg-main-cart-payment-lr-right-form-total">Amount : <span class="success-text"><?php echo $currency . $paypalResult['PAYMENTREQUEST_0_AMT'] ?></span></div>
                                    <?php if ($paypalResult['PAYMENTREQUEST_0_SHIPTOCOUNTRYNAME']!==$countryList[$country]['name']): ?>
                                    <p class="bg-danger dg-main-payment-dangerdec">Your shipping country is <?php echo $paypalResult['PAYMENTREQUEST_0_SHIPTOCOUNTRYNAME'] ?>. However, you are placing an order in our <?php echo $countryList[$country]['name'] ?> site. Please use <?php echo $paypalResult['PAYMENTREQUEST_0_SHIPTOCOUNTRYNAME'] ?> site and try again. </p>
                                    <?php endif; ?>
                                    <button type="submit" class="dg-main-cart-payment-lr-right-form-submit btn btn-success btn-lg" <?php if ($paypalResult['PAYMENTREQUEST_0_SHIPTOCOUNTRYNAME']!==$countryList[$country]['name']) echo 'disabled="disabled"' ?>><i class="fa fa-lock"></i> Confirm My Payment</button>
                                    <div class="dg-main-cart-payment-lr-right-form-secure">
                                        <span class="methodpaypal"><i class="fa fa-lock"></i> You will be <span class="success-text">redirected to PayPal's secure website</span> to complete your order</span>

                                        <span class="methodcc"><i class="fa fa-lock"></i> Your payment for this purchase is processed securely.</span>
                                    </div>
                                    <!--</form>-->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="cartulboxli"></div>
<?php echo $foot ?>
<script>
    $(document).ready(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
        $("input").iCheck('check');
    });

    function check_cart() {

        ifValid = true;
        $(".dg-requiredfield").each(function () {
            if (!$(this).val()) {
                ifValid = false;
                $(this).css('border-color', '#dd514c');
            }
        });

        if (!ifValid) {
            $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in all mandatory fields marked *', position: "bottom"});
            return false;
        }

        if (ifValid && !$("#email").val().match(/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/)) {
            $.notifyBar({cssClass: "dg-notify-error", html: 'Please provide a valid email address', position: "bottom"});
            return false;
        }
        $('.dg-main-cart-payment-lr-right-form-submit').attr('disabled', 'disabled');
        $('.dg-main-cart-payment-lr-right-form-submit').html('<i class="fa fa-lock"></i> Payment in progress ...');
    }

</script>
</body>
</html>