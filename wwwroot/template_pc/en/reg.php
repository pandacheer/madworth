<?php echo $head; ?>
<div class="dg-main">
    <div class="container">
        <div class="row">
            <div class="col-xs-10 col-xs-12">
                <div class="dg-main-reg">
                    <div class="dg-title">Sign up and get a <span style="color:#FF666C">$10 coupon</span></div>
                    <table class="dg-main-reg-table">
                        <tr>
                            <td class="dg-main-reg-table-left">
                                <?php // echo validation_errors(); ?>

                                <!--<div id="resultMessage" style="margin: 5px;"></div>-->
                                <?php echo form_open('reg/add', array('id' => 'regForm')); ?>
                                <div class="form-group">
                                    <label for="email">Email address</label>
                                    <input type="email" data-valid="0" class="form-control" id="email" name="email" onkeypress="if (event.keyCode == 13 || event.which == 13) {
                                                return false;
                                            }">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" data-valid="0" class="form-control" id="password" name="password">
                                </div>
                                <div class="form-group">
                                    <label for="verifyPassword">Confirm Password</label>
                                    <input type="password" data-valid="0" class="form-control" id="verifyPassword" name="verifyPassword">
                                </div>
                                <div class="form-group" id="verifyCode-div" <?php echo ($this->session->userdata('Verification')) && $this->session->userdata('Verification')['clickTimes'] > 2 ? 'style="display:block"' : 'style="display:none"' ?> >
                                    <label for="verifyCode">Verify Code</label>
                                    <input type="text" class="form-control"  id="reg-verifyCode" name="myCode">
                                    <div class="dg-main-reg-table-left-code">
                                        <img src="/reg/vcode" onclick="this.src = '/reg/vcode/' + Math.random()"/>
                                    </div>
                                </div>
                                <div class="form-group dg-main-reg-table-left-terms">By clicking the "Join Now" button, I fully and unconditionally agree to comply with all of the <a href="/pages/privacy-policy">terms and conditions</a>.</div>
                                <button type="button" id="btn_reg" class="btn btn-success btn-lg" >Join Now</button>
                                <?php if($fb_login):?>
                                <div class="dg-topnav-account-myaccount-login-box-or">
                                    <hr>
                                    <span style="color: black">or</span>
                                </div>
                                <a class="btn btn-facebook dg-topnav-account-myaccount-login-box-fbloginbtn" href="<?php echo $fb_login;?>"><i class="fa fa-facebook-square fa-lg"></i>  Login with Facebook</a>
                                <?php endif;?>
                                <?php echo form_close(); ?>
                            </td>
                            <td class="dg-main-reg-table-right">
                                <div class="dg-main-reg-table-right-fb">
                                    <a href="/login"><button class="btn btn-drgrab btn-lg dg-main-reg-table-right-fb-button">Log In</button></a>
                                    <div class="dg-main-reg-table-right-title">Already Have an Account?</div>
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


<script type="text/javascript">
    enterbuttont('.dg-main-reg-table-left');
    $('#email').emailpop();
    $('#email').blur(function () {
        if (!checkInput(false)) {
            return false;
        }
        $.post("/reg/validationEmail", {
            email: $('#email').val()
        }, function (result) {
            if (!result) {
                $('#email').css("border-color", "red");
                $.notifyBar({cssClass: "dg-notify-error", html: 'Your email address is already registered.', position: "bottom"});

                return false;
            } else {
                $('#email').css("border-color", "green");
            }

        });
    });
    $('#reg-verifyCode').bind('input propertychange', function () {
        checkVerifyCode($('#reg-verifyCode'));
    });


    $('#btn_reg').click(function () {
        if (!checkInput(true)) {
            return false;
        }
        $('#btn_reg').prop('disabled', true);
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('reg/add') ?>",
            dataType: 'json',
            data: $("#regForm").serialize(),
            success: function (result) {
                if (result.success) {
                    /* $.notifyBar({cssClass: "dg-notify-success", delay: 1000, html: "success", position: "bottom"}); */
                    self.location = "/home/showSuccess/S2002?jumpUrl=<?php echo $refererUrl ?>";
                } else {
                    if (result.clickTimes > 2) {
                        $("#verifyCode-div").css("display", "block");
                    }
                    $.notifyBar({cssClass: "dg-notify-error", delay: 1000, html: result.errorMessage, position: "bottom"});
                    $('#btn_reg').prop('disabled', false);
                }
            }
        });
    });

    $("#email,#password,#verifyPassword").focus(function () {
        $(this).removeAttr('style');
    });

    function checkInput(all) {
        if ($('#email').val() === '') {
            $.notifyBar({cssClass: "dg-notify-error", html: 'Please enter a valid email address', position: "bottom"});
            $('#email').css("border-color", "red");
            return false;
        }
//        if (!(/^([\w-_]+(?:\.[\w-_]+)*)@((?:[a-z0-9]+(?:-[a-zA-Z0-9]+)*)+\.[a-z]{2,6})$/i.test($('#email').val()))) {
        if (($('#email').val() === '') || (!(/^[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?$/i.test($('#email').val())))) {
            $.notifyBar({cssClass: "dg-notify-error", html: 'Please enter a valid email address', position: "bottom"});
            $('#email').css("border-color", "red");
            return false;
        }
        if (all) {
            if (!/^[a-zA-Z0-9_]{5,20}$/i.test($('#password').val()) || ($('#password').val() === '')) {
                $.notifyBar({cssClass: "dg-notify-error", html: 'The password must be at least 5 characters - letters or numbers.', position: "bottom"});
                $('#password').css("border-color", "red");
                return false;
            }

            if ($('#password').val() !== $('#verifyPassword').val()) {
                $.notifyBar({cssClass: "dg-notify-error", html: 'The Confirm Password field does not match the Password field.', position: "bottom"});
                $('#password').css("border-color", "red");
                $('#verifyPassword').css("border-color", "red");
                return false;
            }
        }
        return true;
    }
    cartempty();
</script>
</body>
</html>
