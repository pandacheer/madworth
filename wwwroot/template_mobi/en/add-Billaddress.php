<?php echo $head; ?>
<div role="main" class="ui-content">
    <div class="dg-main-form">
        <div class="dg-pagetitle">New Billing Address</div>
        <?php echo form_open('personal/billAddressInsert', 'onsubmit="return check_address()"'); ?>
        <input type="hidden" name="receive_id" id="receive_id" value="0">
        <div class="ui-grid-a">
            <div class="ui-block-a">
                <label>First Name<span class="red">*</span></label>
                <input type="text"  id="firstname" name="firstname" class="dg-requiredfield">
            </div>
            <div class="ui-block-b ">
                <label>Last Name<span class="red">*</span></label>
                <input type="text"  id="lastname" name="lastname" class="dg-requiredfield">
            </div>
        </div>
        <div class="ui-grid-a">
            <div class="ui-block-a" style="width:67%">
                <label>Address<span class="red">*</span></label>
                <input type="text" id="address1" name="address1" class="dg-requiredfield">
            </div>
            <div class="ui-block-b" style="width:33%">
                <label>Apt,Suite,etc</label>
                <input type="text"  id="apt" name="apt" placeholder="optional">
            </div>
        </div>
        <div class="ui-grid-a">
            <div class="ui-block-a" style="width:67%">
                <label><?php echo $addCountry['city'] ?><span class="red">*</span></label>
                <input type="text"  id="suburb" name="suburb" class="dg-requiredfield" value="<?php if (count($States) == 0) echo $countryList[$country]['name'] ?>">
            </div>
            <div class="ui-block-b" style="width:33%">
                <label><?php echo $addCountry['zipcode'] ?><span class="red">*</span></label>
                <input type="text" id="postcode" name="postcode" class="dg-requiredfield">
            </div>
        </div>
        <div class="ui-grid-a">
            <div class="ui-block-a" style="width:67%">
                <label><?php echo $addCountry['state'] ?><span class="red">*</span></label>
                <select data-theme="c" name="state"  id="state">
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
            <div class="ui-block-b" style="width:33%">
                <label>COUNTRY<span class="red">*</span></label>
                <input type="text"  id="country" name="country" value="<?php echo $countryList[$country]['name'] ?>" readonly class="dg-requiredfield">
            </div>
        </div>
        <div class="dg-main-form-button"> 
            <button data-theme="g" id="btnSave">Save</button> 
        </div>
        <a href="/personal/address"><button  type="button" data-theme="c" id="close">Cancel</button></a>
        <?php echo form_close(); ?>

    </div>
</div>
<?php echo $foot; ?>
</div>

<script>
    function check_address() {
        ifValid = true;

        $(".dg-requiredfield").each(function () {
            if (!$(this).val()) {
                ifValid = false;
                $(this).parent().addClass('dg-required-red');
            }
        });
        if ($('#state').val() == "Please select your <?php echo $addCountry['state'] ?>") {
            $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in all mandatory fields marked *', position: "bottom"});
            $('#state').parent().parent().addClass('dg-required-red');
            ifValid = false;
        }
        if (!ifValid) {
            $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in all mandatory fields marked *', position: "bottom"});
            return false;
        }
        if (ifValid) {
            return true;
        }
    }

    $(function () {
        $(".dg-requiredfield").each(function () {
            $(this).bind('focus', function () {
                $(this).parent().removeClass('dg-required-red');
            });
        })
        $('#state').hover(function () {
            $('#state').parent().parent().removeClass('dg-required-red');
        })
    })
</script>
</body>
</html>