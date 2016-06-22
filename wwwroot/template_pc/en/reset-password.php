<?php echo $head; ?>
<div class="dg-main">
    <div class="container">
        <div class="row">
            <div class="col-xs-10 col-xs-12">
                <div class="dg-main-reg">
                    <div class="dg-title">Reset your password!</div>
                    <table class="dg-main-reg-table">
                        <tr>
                            <td class="dg-main-for-table-left">
                                <div id='resultMessage' style="margin: 5px;"></div>
                                <?php echo form_open('/forget/update', array('id' => 'updateForm')); ?>
                                <input type="hidden" name="check_Token" value="<?php echo $forget_email ?>">
                                <input type="hidden" name="check_id" value="<?php echo $forget_id ?>">
                                <div class="form-group">
                                    <label for="forget_email">Email address<span>*</span></label>
                                    <input type="email" class="form-control" id="forget_email" name="forget_email" value="<?php echo $true_email ?>" readonly="readonly">
                                </div>
                                <div class="form-group">
                                    <label for="password">New Password<span>*</span></label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <div class="form-group">
                                    <label for="verifyPassword">Confirm New Password<span>*</span></label>
                                    <input type="password" class="form-control" id="verifyPassword" name="verifyPassword">
                                </div>
                                <!--<div class="form-group"><input type="checkbox" checked="true"> I have read and agree to the terms and conditions.</div>-->
                                <button type="button" class="btn btn-success btn-lg" id="btnReset">Reset Now</button>
                                <?php echo form_close(); ?>
                            </td>
                            <td class="dg-main-reg-table-right">
                                <div class="dg-main-reg-table-right-fb">
                                    <div class="dg-main-reset-ticker">
                                        <i class="fa fa-expeditedssl fa-lg"></i>
                                        <div class="dg-main-reset-title">Complete Your Password Reset</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <?php echo $shoppingcart ?>

        </div>
    </div>
</div>  

<?php echo $foot; ?>

<script>
    cartempty();
    $('#verifyPassword').keypress(function (e) {
        var keyCode = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
        if (keyCode === 13) {
           $('#btnReset').trigger('click');
        };
    });
    enterbutton('form[action="/reg/login"]');
    $('#btnReset').click(function () {
        if (!(/^[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?$/i.test($('#forget_email').val()))) {
            $.notifyBar({cssClass: "dg-notify-error", html: 'Please enter a valid email address', position: "bottom"});
            return false;
        }
        if (!/^[a-zA-Z0-9_]{6,16}$/i.test($('#password').val())) {
            $.notifyBar({cssClass: "dg-notify-error", html: 'The password must be at least 5 characters - letters or numbers.', position: "bottom"});
            return false;
        }
        if ($('#verifyPassword').val() !== $('#password').val()) {
            $.notifyBar({cssClass: "dg-notify-error", html: 'The Confirm Password field does not match the Password field.', position: "bottom"});
            return false;
        }
        $('#updateForm').submit();
    });
</script>
</body>
</html>