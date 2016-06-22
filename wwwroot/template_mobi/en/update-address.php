<?php echo $head; ?>
<div role="main" class="ui-content">
    <div class="dg-main-form">
        <div class="dg-pagetitle">Edit Shipping Address</div>
        <?php echo form_open('personal/addressInsert', 'onsubmit="return check_address()"'); ?>
        <input type="hidden" name="receive_id" id="receive_id" value="<?= $result['receive_id'] ?>">
        <div class="ui-grid-b">
            <div class="ui-block-a">
                <label>First Name<span class="red">*</span></label>
                <input type="text"  class="dg-requiredfield" id="firstname" name="firstname" value="<?= $result['receive_firstName'] ?>">
            </div>
            <div class="ui-block-b ">
                <label>Last Name<span class="red">*</span></label>
                <input type="text"  class="dg-requiredfield" id="lastname" name="lastname" value="<?= $result['receive_lastName'] ?>" >
            </div>
            <div class="ui-block-c " >
                <label>Phone</label>
                <input type="text"  id="phone" name="phone" value="<?= $result['receive_phone'] ?>" placeholder="optional">
            </div>
        </div>
        <div class="ui-grid-a">
            <div class="ui-block-a" style="width:67%">
                <label>Address<span class="red">*</span></label>
                <input type="text" class="dg-requiredfield" id="address1" name="address1" value="<?= $result['receive_add1'] ?>">
            </div>
            <div class="ui-block-b" style="width:33%">
                <label>Apt,Suite,etc</label>
                <input type="text"  id="apt" name="apt" value="<?= $result['receive_add2'] ?>" placeholder="optional">
            </div>
        </div>
        <div class="ui-grid-a">
            <div class="ui-block-a" style="width:67%">
                <label><?php echo $addCountry['city'] ?><span class="red">*</span></label>
                <input type="text"  class="dg-requiredfield" id="suburb" value="<?= $result['receive_city'] ?>" name="suburb">
            </div>
            <div class="ui-block-b" style="width:33%">
                <label><?php echo $addCountry['zipcode'] ?><span class="red">*</span></label>
                <input type="text" class="dg-requiredfield" id="postcode" value="<?= $result['receive_zipcode'] ?>" name="postcode" >
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
                            <option value="<?php echo $StateName ?>"<?php if ($StateName == $result['receive_province']) {
                        echo "selected='selected'";
                    } ?>><?php echo $StateName ?></option>
    <?php endforeach; ?>
<?php endif; ?>
                </select>        
            </div>
            <div class="ui-block-b" style="width:33%">
                <label>COUNTRY<span class="red">*</span></label>
                <input type="text"  id="country" name="country" value="<?= $result['receive_country'] ?>" readonly >
            </div>
        </div>
        <div class="dg-main-form-button">
            <button data-theme="g">Save</button>
            <a href="/personal/address"><button  type="button" data-theme="c" id="close">Cancel</button></a>
        </div>
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

    /*function del() {
     var rid=$("#del_address").data("rid");
     $.ajax({
     type: "POST",
     url: "<?php echo site_url('personal/addressDelete') ?>",
     dataType: 'json',
     data: {receive_id:rid},
     success: function (result) {
     if (result) {
     self.location = '/personal/address';
     }else{
     $.notifyBar({ cssClass: "dg-notify-error", html: 'Failed to process your request, please try again.' ,position: "bottom" });
     }
     }
     }) 
     }
             
     $("#btnPrimary").click(function () {
     $.ajax({
     type: "POST",
     url: "<?php echo site_url('personal/addressDefault') ?>",
     dataType: 'json',
     data: $("form").serialize(),
     success: function (result) {
     if (result) {
     history.back();
     } 
     }
     });
     });*/
</script>

</body>
</html>