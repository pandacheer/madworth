<?php echo $head; ?>
<div role="main" class="ui-content">
    <h4 class="dg-main-pay-title">Choose a Payment Method</h4>
    <div class="dg-main-pay-header">
        <div class="ui-grid-a">
            <div class="ui-block-a">
                <div class="dg-main-pay-msg">
                    <img src="<?php echo $cdn ?>img/lock.png"
                         style="display: inline-block; float: left;" class="dg-main-pay-msg-img">
                    <p class="dg-main-pay-msg-p">Secure
                        Credit Card Payment</p><h6>Payments are encrypted with SSL security</h6>
                </div>
            </div>
            <div class="ui-block-b">
                <a href="https://seal.digicert.com/seals/popup/?tag=bbFrnPZh&url=www.drgrab.com&lang=en&cbr=1441097102978"><img src="<?php echo $cdn ?>img/cascade.png" style="float: right;"></a>
            </div>
        </div>
    </div>
    <form action="<?php echo site_url('order/checkoutPayment') ?>" method="POST" onsubmit="return check_cart()">
        <div class="dg-main-form" style="margin-bottom:2em">
            <div class="dg-pagetitle">Shipping Address</div>

            <div class="ui-grid-a">
                <div class="ui-block-a">
                    <input type="hidden" id="address_id" name="address_id" value="0" />
                    <label>Name<span class="red">*</span></label>
                    <input type="text" name="firstname"  value="<?php echo $paypalResult['PAYMENTREQUEST_0_SHIPTONAME'] ?>" class="form-control dg-requiredfield">
                </div>
                <div class="ui-block-b">
                    <label>Phone</label>
                    <input type="text" name="phone"  value="<?php echo array_key_exists('PAYMENTREQUEST_0_SHIPTOPHONENUM', $paypalResult) ? $paypalResult['PAYMENTREQUEST_0_SHIPTOPHONENUM'] : '' ?>" >
                </div>
            </div>

            <label>Email<span class="red">*</span></label>
            <input type="text" name="emailaddress" id="email" value="<?php echo $this->session->userdata('member_email') ? $this->session->userdata('member_email') : $paypalResult['EMAIL'] ?>" class="form-control dg-requiredfield">

            <div class="ui-grid-a">
                <div class="ui-block-a" style="width:67%">
                    <label>Address<span class="red">*</span></label>
                    <input type="text" name="address"  value="<?php echo $paypalResult['PAYMENTREQUEST_0_SHIPTOSTREET'] ?>" class="form-control dg-requiredfield">
                </div>
                <div class="ui-block-b" style="width:33%">
                    <label>Apt,Suite,etc</label>
                    <input type="text" name="apt" value="<?php echo array_key_exists('PAYMENTREQUEST_0_SHIPTOSTREET2', $paypalResult) ? $paypalResult['PAYMENTREQUEST_0_SHIPTOSTREET2'] : '' ?>">
                </div>
            </div>
            <div class="ui-grid-a">
                <div class="ui-block-a" style="width:67%">
                    <label><?php echo $addCountry['city'] ?><span class="red">*</span></label>
                    <input type="text" name="suburb"  value="<?php echo array_key_exists('PAYMENTREQUEST_0_SHIPTOCITY', $paypalResult) ? $paypalResult['PAYMENTREQUEST_0_SHIPTOCITY'] : $countryList[$country]['name'] ?>" class="form-control dg-requiredfield">
                </div>
                <div class="ui-block-b" style="width:33%">
                    <label><?php echo $addCountry['zipcode'] ?><span class="red">*</span></label>
                    <input type="text" name="postcode"  value="<?php echo $paypalResult['PAYMENTREQUEST_0_SHIPTOZIP'] ?>" class="form-control dg-requiredfield">
                </div>
            </div>
            <div class="ui-grid-a">
                <div class="ui-block-a" style="width:67%">
                    <label><?php echo $addCountry['state'] ?><span class="red">*</span></label>
                    <input type="text" name="state"  value="<?php echo array_key_exists('PAYMENTREQUEST_0_SHIPTOSTATE', $paypalResult) ? $paypalResult['PAYMENTREQUEST_0_SHIPTOSTATE'] : $countryList[$country]['name'] ?>" class="form-control dg-requiredfield">      
                </div>
                <div class="ui-block-b" style="width:33%">
                    <label>COUNTRY<span class="red">*</span></label>
                    <input type="text" readonly id="country" value="<?php echo $countryList[$country]['name'] ?>" readonly>

                </div>
            </div>
            <!--</form>--> 
        </div>
        <div class="dg-main-pay">
            <div class="dg-main-pay-list" id="hidelist">
                <label>
                    <div class="ui-grid-a">
                        <div class="ui-block-a">
                            <div class="iradio_square-blue" style="float:left;" id="input2"></div>
                            <div class="dg-main-pay-list-title ">
                                <input type="radio" name="pay_type"
                                       class="dg-main-pay-list-title-input"
                                       value="1" data-role="none" checked  style="opacity: 0;"> 
                                <small>Paypal</small>
                            </div>
                        </div>
                        <div class="ui-block-b">
                            <div class=" dg-main-pay-list-img">
                                <img src="<?php echo $cdn ?>img/paypal.png">
                            </div>
                        </div>
                    </div>   
                </label>
            </div>
        </div>
        <div class="dg-main-pay-total">
            Amount : <span class="success-text"><?php echo $currency . $paypalResult['PAYMENTREQUEST_0_AMT'] ?></span>
        </div>
        <?php if ($paypalResult['PAYMENTREQUEST_0_SHIPTOCOUNTRYNAME']!==$countryList[$country]['name']): ?>
        <p class="dg-main-payment-danger">Your shipping country is <?php echo $paypalResult['PAYMENTREQUEST_0_SHIPTOCOUNTRYNAME'] ?>. However, you are placing an order in our <?php echo $countryList[$country]['name'] ?> site. Please use <?php echo $paypalResult['PAYMENTREQUEST_0_SHIPTOCOUNTRYNAME'] ?> site and try again. </p>
        <?php endif; ?>
        <button type="submit" data-theme="g" id="payconfirm" <?php if ($paypalResult['PAYMENTREQUEST_0_SHIPTOCOUNTRYNAME']!==$countryList[$country]['name']) echo 'disabled="disabled"' ?>>
            <i class="fa fa-lock"></i> Pay Securely Now
        </button>
    </form>
    <div class="dg-main-pay-secure">
        <span class="methodpaypal"><i class="fa fa-lock"></i> You will
            be <span class="success-text">redirected to PayPal's secure
                website</span> to complete your order</span> <span
            class="methodcc"><i class="fa fa-lock"></i> Your payment for
            this purchase is processed securely.</span>
    </div>
</div>
<?php echo $foot; ?>
</div>
<script>
    $(function () {
        $('#input2').addClass('checked');
        $(".cchide").show();
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
        $('#payconfirm').attr('disabled', 'disabled');
        $('#payconfirm').html('Payment in progress ...');
    }
</script>

</body>

</html>